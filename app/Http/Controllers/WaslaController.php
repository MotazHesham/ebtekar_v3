<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class WaslaController extends Controller
{
    public function endpoint($uri)
    {
        return 'https://waslaeg.com/api/' . $uri;
    } 

    public function login(Request $request){ 
        $response = $this->post(
            $this->endpoint("v1/company/login"), 
            [
                'email' => $request->email,
                'password' => $request->password,
            ],
            false
        ); 
        if($response['errNum'] == 200){
            $user = Auth::user();
            $user->wasla_token = $response['data']['user_token'];
            $user->wasla_company_id = $response['data']['company_id'];
            $user->save();
            return 1; 
        }else{
            return 0; 
        } 
    }

    public function wasla_change_status(Request $request){
        try{
            $raw = ReceiptCompany::where('order_num',$request->receipt_code)->first();
            if(!$raw){
                $raw = Order::where('order_num',$request->receipt_code)->first();
                if(!$raw){
                    $raw = ReceiptSocial::where('order_num',$request->receipt_code)->first();
                }
            }
            
            if($raw){
                if($request->status == 'done'){
                    $raw->delivery_status = 'delivered'; 
                    $raw->done_time = time();
                }elseif($request->status == 'with_delivery_man'){
                    $raw->delivery_status = 'on_delivery';
                }elseif($request->status == 'delay'){
                    $raw->delivery_status = 'delay';
                    $raw->delay_reason = $request->delay_date;
                }elseif($request->status == 'returned'){
                    $raw->delivery_status = 'cancel';
                    $raw->cancel_reason = $request->return_note . " - " . $request->return_reason;
                }
                $raw->save();
            }

        }catch(\Exception $ex){
            return response()->json('SomeThing Went Wrong in update order status in ebtekar');
        }
    }

    public function store_order($data){
        return $this->post($this->endpoint("v1/company/orders/add"), $data, true); 
    }

    public function countries(){  
        return $this->get($this->endpoint("v1/company/countries"), [], true); 
    }

    public function profile(){  
        return $this->get($this->endpoint("v1/company/profile"), [], true); 
    }

    public function post($url, $data,$auth)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($auth){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(json_encode($data)),
                    'Authorization: Bearer ' . Auth::user()->wasla_token)
            );
        }else{
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(json_encode($data)))
            );
        }

        $results = curl_exec($ch);

        return json_decode($results, true);
    }


    public function get($url, $data, $auth)
    {
        $params = '';
        foreach($data as $key=>$value)
            $params .= $key.'='.$value.'&';

        $params = trim($params, '&');

        $ch = curl_init($url."?".$params);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . Auth::user()->wasla_token,
        ));

        return json_decode(curl_exec($ch), true);
    }
}