<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotification;
use App\Models\CommissionRequest;
use App\Models\CommissionRequestOrders;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\UserAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function orders(){
        $site_settings = get_site_setting();
        global $website_setting_id;
        $website_setting_id = $site_settings->id;
        $orderDetails = OrderDetail::with('order','product')->whereHas('order' ,function($query){
            $query->where('user_id',Auth::id())->where('website_setting_id',$GLOBALS['website_setting_id']);
        })->orderBy('created_at','desc')->paginate(10);
        return view('frontend.orders',compact('orderDetails'));
    }

    public function request_commission(Request $request){
        if($request->orders == null){
            alert('Please Select at least one order','','error');
            return back();
        }else{

            DB::beginTransaction();
            $commission_request_orders = [];
            $total_commission = 0;

            $commission_request = new CommissionRequest();
            $commission_request->user_id = Order::find($request->orders[0])->user_id;
            $commission_request->created_by_id = Auth::user()->id;
            $commission_request->total_commission = $total_commission;
            $commission_request->payment_method = $request->payment_method;
            $commission_request->transfer_number = $request->transfer_number;
            $commission_request->save();

            foreach ($request->orders as $order_id) {
                $order = Order::find($order_id);
                $commission_request_orders[] = [
                    'order_id' => $order_id,
                    'commission' => $order->commission + $order->extra_commission,
                    'commission_request_id' => $commission_request->id
                ];
                $total_commission += $order->commission + $order->extra_commission;

                $order->commission_status = 'requested';
                $order->save();
            }

            $commission_request->total_commission = $total_commission;
            $commission_request->save();

            CommissionRequestOrders::insert($commission_request_orders);

            $title = Auth::user()->email;
            $body = 'طلب سحب جديد';
            $userAlert = UserAlert::create([
                'alert_text' => $title . ' ' . $body . ' من ',
                'alert_link' => route('admin.commission-requests.index'),
                'type' => 'request_commission', 
            ]);   
            // only users has permission commission_request_show can see the notification
            $allowed_users_ids = User::where('user_type','staff')->whereHas('roles.permissions',function($query){
                $query->where('permissions.title','commission_request_show');
            })->pluck('id')->all();
            $userAlert->users()->sync($allowed_users_ids); 


            $site_settings = get_site_setting();
            
            // send push notification to users has the permission and has a device_token to send via firebase
            $tokens = User::whereNotNull('device_token')->whereHas('roles.permissions',function($query){
                $query->where('permissions.title','commission_request_show');
            })->where('user_type','staff')->pluck('device_token')->all();   
            SendPushNotification::dispatch($title, $body, $tokens,route('admin.commission-requests.index'),$site_settings);  // job for sending push notification 
            DB::commit();

            alert('Commission Requested Successfully','','success');
            return redirect()->route('frontend.orders');
        } 
    }

    public function commission_requests(){  
        $commission_requests = CommissionRequest::where('user_id',Auth::id())->with(['user', 'created_by', 'done_by_user','commission_request_orders.order'])
                                                    ->orderBy('created_at','desc')
                                                    ->paginate(10); 
        return view('frontend.commission_requests',compact('commission_requests'));
    }

    public function track($id){
        $order = Order::findOrFail($id);
        return view('frontend.order-track',compact('order'));
    }



    public function success($id){
        $order = Order::findOrFail($id);
        return view('frontend.order-success',compact('order'));
    }
}
