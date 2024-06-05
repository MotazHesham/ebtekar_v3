<?php

namespace App\Http\Controllers;

use App\Models\DeviceUserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie; 
use Auth;
use Browser;

class PushNotificationController extends Controller
{ 

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
            if(Auth::check()){
                auth()->user()->update(['device_token'=>$request->token]);
                DeviceUserToken::updateOrCreate(
                    [
                        'user_id' => auth()->user()->id,
                        'device_token' => $request->token,
                    ],
                    [
                        'userAgent' => Browser::userAgent(),
                        'device_type' => Browser::deviceType(),
                        'browser_type' => Browser::browserFamily(),
                        'browser_version' => Browser::browserVersion(),
                        'platform_type' => Browser::platformFamily(),
                        'playform_version' => Browser::platformVersion(),
                        ]
                    );
            }
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
