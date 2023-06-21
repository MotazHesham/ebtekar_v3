<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\GeneralSetting;


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
    public function sendNotification($title, $body, $firebaseToken, $link)
    { 

        $SERVER_API_KEY = config('app.server_api_key');

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "icon" => asset(GeneralSetting::first()->logo->getUrl('thumb')),
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
