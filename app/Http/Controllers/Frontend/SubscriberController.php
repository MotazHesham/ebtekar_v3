<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function subscribe(Request $request){
        $subscriber = Subscriber::where('email',$request->email)->first();
        if($subscriber){
            toast('Already Subscribed','warning');
            return redirect()->route('home');
        }else{
            $subscriber = Subscriber::create($request->all());
            toast('Successfully Subscribed','success');
            return redirect()->route('home');
        }
    }
}
