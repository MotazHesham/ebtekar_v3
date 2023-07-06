<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie; 


class PushNotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    { 
        if(Cookie::has('device_token')){ 
            return response()->json(['already have token']);
        }else{ 
            Cookie::queue(Cookie::make('device_token',$request->token,1000));
            auth()->user()->update(['device_token'=>$request->token]);
            return response()->json(['token saved successfully.']);
        } 
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification($title, $body, $userTokens, $link,$site_settings)
    { 

        $SERVER_API_KEY = config('app.fcm_token_key'); 
        $data = [
            "registration_ids" => $userTokens,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "icon" => $site_settings->logo ? $site_settings->logo->getUrl('thumb') : '',
                "custom_data" => [
                    "click_action" => $link
                ],
                "content_available" => true,
                "priority" => "high",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return $response;
    }
}
