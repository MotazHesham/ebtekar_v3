<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Frontend\CheckoutController;
use Illuminate\Http\Request; 
use App\Models\Order;
use BaklySystems\PayMob\Facades\PayMob;

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

        // Request body
        $json = [
            'api_key' => 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TVRBME56RTVMQ0p1WVcxbElqb2lhVzVwZEdsaGJDSjkuLTR5b2s2UDZCbUxjdERnM2J0RjV2XzBMQ1dUa21ncEhjbElTSUFIV0p3WUtGTWpBY3R5cWVhVVN1bDc4T2RRZGZnU2xwZ2JPQjZ0dzFVRzdrYW02bEE=',
        ];

        // Send curl
        $auth = $this->cURL(
            'https://accept.paymobsolutions.com/api/auth/tokens',
            $json
        );// login PayMob servers 
        if (property_exists($auth, 'detail')) { // login to PayMob attempt failed. 
            alert("SomeThing Went Wrong!","",'error');
            return redirect()->route('frontend.payment_select');
        }

        $order = Order::withoutGlobalScope('completed')->find($order_id);
        $totalCost = $order->calc_total_for_client();
        // Request body
        $json = [
            'merchant_id'            => $auth->profile->id,
            'amount_cents'           =>   $totalCost * 100,
            'merchant_order_id'      => $order->id,
            'currency'               => 'EGP',
            'notify_user_with_email' => true
        ];

        // Send curl
        $paymobOrder = $this->cURL(
            'https://accept.paymobsolutions.com/api/ecommerce/orders?token='.$auth->token,
            $json
        ); 
        // Duplicate order id
        // PayMob saves your order id as a unique id as well as their id as a primary key, thus your order id must not
        // duplicate in their database.
        if (isset($paymobOrder->message)) {
            if ($paymobOrder->message == 'duplicate') {
                alert("SomeThing Went Wrong!","",'error');
                return redirect()->route('frontend.payment_select');
            }
        }

        $order->update(['paymob_order_id' => $paymobOrder->id]); // save paymob order id for later usage. 
        
        // Request body
        $json = [ 
            'amount_cents' =>  $totalCost * 100,
            'expiration'   => 36000,
            'order_id'     => $paymobOrder->id,
            "billing_data" => [
                "email"        => auth()->user()->email ?? '',
                "first_name"   => $fname,
                "last_name"    => $lname,
                "phone_number" => $phone,
                "city"         => $order->shipping_address,
                "country"      => $order->shipping_country->name ?? '',
                'street'       => 'null',
                'building'     => 'null',
                'floor'        => 'null',
                'apartment'    => 'null'
            ],
            'currency'            => 'EGP',
            'card_integration_id' => $integration_id
        ];
        // Send curl
        $payment_key = $this->cURL(
            'https://accept.paymobsolutions.com/api/acceptance/payment_keys?token='.$auth->token,
            $json
        ); 
        // return $auth->token; 
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
        alert("Your order has been placed successfully","",'success');
        return redirect()->route('frontend.orders.success',$order->id);
    }

    /**
     * Transaction voided.
     *
     * @param  object  $order
     * @return void
     */
    protected function voided($order)
    { 
        alert("SomeThing Went Wrong!!!","",'error');
        return redirect()->route('frontend.payment_select');
    }

    /**
     * Transaction refunded.
     *
     * @param  object  $order
     * @return void
     */
    protected function refunded($order)
    {
        alert("SomeThing Went Wrong!!!","",'error');
        return redirect()->route('frontend.payment_select');
    }

    /**
     * Transaction failed.
     *
     * @param  object  $order
     * @return void
     */
    protected function failed()
    {
        alert("SomeThing Went Wrong!!!","",'error');
        return redirect()->route('frontend.payment_select');
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
        $secret = config('nafezly-payments.PAYMOB_HMAC');
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

    protected function cURL($url, $json)
    {
        // Create curl resource
        $ch = curl_init($url);

        // Request headers
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }
}
