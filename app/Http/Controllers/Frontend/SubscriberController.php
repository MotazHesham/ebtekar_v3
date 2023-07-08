<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscribeRequest;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function subscribe(StoreSubscribeRequest $request){
        $subscribe = Subscribe::where('email',$request->email)->first();
        if($subscribe){
            toast('Already Subscribed','warning');
            return redirect()->route('home');
        }else{
            $subscribe = Subscribe::create($request->all());
            toast('Successfully Subscribed','success');
            return redirect()->route('home');
        }
    }
}
