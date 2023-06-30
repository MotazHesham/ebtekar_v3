<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BaklySystems\PayMob\Facades\PayMob;
use App\Models\Order;
use App\Http\Controllers\CheckoutController;


class PayMobController extends Controller
{

    /**
     * Display checkout page.
     *
     * @param  int  $orderId
     * @return Response
     */
    public function checkingOut($integration_id ,$iframe_id, $order_id,$fname,$lname,$phone)
    {

        $auth        = PayMob::authPaymob(); // login PayMob servers

        if (property_exists($auth, 'detail')) { // login to PayMob attempt failed.
            toast('SomeThing Went Wrong!','error');
            return redirect()->route('frontend.checkout');
        }

        $order = Order::withoutGlobalScope('completed')->find($order_id);
        $totalCost = $order->required_to_pay + $order->extra_commission + $order->shipping_country_cost - $order->deposit_amount;
        $paymobOrder = PayMob::makeOrderPaymob( // make order on PayMob
            $auth->token,
            $auth->profile->id,
            $totalCost * 100,
            $order->id
        );
        // Duplicate order id
        // PayMob saves your order id as a unique id as well as their id as a primary key, thus your order id must not
        // duplicate in their database.
        if (isset($paymobOrder->message)) {
            if ($paymobOrder->message == 'duplicate') {
                toast('SomeThing Went Wrong!','error');
                return redirect()->route('frontend.checkout');
            }
        }

        $user = auth()->user();

        if($user == null){
            $email = 'NA';
            $address = 'NA';
        }else{
            $email = $user->email;
            $address = $user->address;
        }
        $order->update(['paymob_order_id' => $paymobOrder->id]); // save paymob order id for later usage.
        $payment_key = PayMob::getPaymentKeyPaymob( // get payment key
            $integration_id,
            $auth->token,
            $totalCost * 100,
            $paymobOrder->id,
            // For billing data
            $email, // optional
            $fname, // optional
            $lname, // optional
            $phone, // optional
            $order->shipping_address, // optional
            $order->shipping_country_name, // optional
        );

        $token = $payment_key->token ?? '';

        return redirect('https://accept.paymob.com/api/acceptance/iframes/'. $iframe_id .'?payment_token='. $token);
    }

    /**
     * Make payment on PayMob for API (mobile clients).
     * For PCI DSS Complaint Clients Only.
     *
     * @param  \Illuminate\Http\Reuqest  $request
     * @return Response
     */
    public function payAPI(Request $request)
    {
        $this->validate($request, [
            'orderId'         => 'required|integer',
            'card_number'     => 'required|numeric|digits:16',
            'card_holdername' => 'required|string|max:255',
            'card_expiry_mm'  => 'required|integer|max:12',
            'card_expiry_yy'  => 'required|integer',
            'card_cvn'        => 'required|integer|digits:3',
        ]);

        $user    = auth()->user();
        $order   = config('paymob.order.model', 'App\Order')::findOrFail($request->orderId);
        $payment = PayMob::makePayment( // make transaction on Paymob servers.
          $payment_key_token,
          $request->card_number,
          $request->card_holdername,
          $request->card_expiry_mm,
          $request->card_expiry_yy,
          $request->card_cvn,
          $order->paymob_order_id,
          $user->firstname,
          $user->lastname,
          $user->email,
          $user->phone
        );

        # code...
    }

    /**
     * Transaction succeeded.
     *
     * @param  object  $order
     * @return void
     */
    protected function succeeded($order)
    {
        $CheckoutController = new CheckoutController;
        $CheckoutController->checkout_done($order->id,'paid');
        flash("Your order has been placed successfully")->success();
        return redirect()->route('order_confirmed',$order->id);
    }

    /**
     * Transaction voided.
     *
     * @param  object  $order
     * @return void
     */
    protected function voided($order)
    {
        flash(__('SomeThing Went Wrong!!!'))->error();
        return redirect()->route('checkout.shipping_info');
    }

    /**
     * Transaction refunded.
     *
     * @param  object  $order
     * @return void
     */
    protected function refunded($order)
    {
        flash(__('SomeThing Went Wrong!!!!'))->error();
        return redirect()->route('checkout.shipping_info');
    }

    /**
     * Transaction failed.
     *
     * @param  object  $order
     * @return void
     */
    protected function failed()
    {
        flash(__('Faild To paid'))->error();
        return redirect()->route('checkout.shipping_info');
    }

    /**
     * Processed callback from PayMob servers.
     * Save the route for this method in PayMob dashboard >> processed callback route.
     *
     * @param  \Illumiante\Http\Request  $request
     * @return  Response
     */
    public function processedCallback(Request $request)
    {
        $data = $request->all();
        $order   = Order::withoutGlobalScope('completed')->find($data['merchant_order_id']);
        if(!$order){
            return $this->failed();
        }
        ksort($data);
        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach($data as $key => $element){
            if(in_array($key,$array)){
                $connectedString .= $element;
            }
        }
        $secret = 'DB38E9ADC9038BAF35B42DEBBBE1FEAD';
        $hashed = hash_hmac('sha512',$connectedString,$secret);
        if($hashed == $hmac){

            // Statuses.
            $isSuccess  = filter_var($request['success'], FILTER_VALIDATE_BOOLEAN);
            $isVoided  = filter_var($request['is_voided'], FILTER_VALIDATE_BOOLEAN);
            $isRefunded  = filter_var($request['is_refunded'], FILTER_VALIDATE_BOOLEAN);
            if ($isSuccess && !$isVoided && !$isRefunded) { // transcation succeeded.
                return $this->succeeded($order);
            } elseif ($isSuccess && $isVoided) { // transaction voided.
                return $this->voided($order);
            } elseif ($isSuccess && $isRefunded) { // transaction refunded.
                return $this->refunded($order);
            } elseif (!$isSuccess) { // transaction failed.
                return $this->failed();
            }
        }else{
            return $this->failed();
        }

    }

    /**
     * Display invoice page (PayMob response callback).
     * Save the route for this method to PayMob dashboard >> response callback route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function invoice(Request $request)
    {
        # code...
    }

}
