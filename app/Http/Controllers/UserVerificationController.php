<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendVerificationMail;
use App\Mail\VerifyUserMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;  
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UserVerificationController extends Controller
{
    public function approve($token)
    {
        $user = User::where('verification_token', $token)->first();
        abort_if(! $user, 404);

        $user->verified           = 1;
        $user->verified_at        = Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        $user->verification_token = null;
        $user->save();

        return redirect()->route('login')->with('message', __('global.emailVerificationSuccess'));
    }

    public function verify(){
        return view('auth.verify');
    }

    public function resend(Request $request){
        $request->validate([
            'email' => 'required|string|email',
        ]); 

        
        $site_settings = get_site_setting(); 
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return abort(404);
        }
        $token     = Str::random(64); 
        $user->verification_token = $token;
        $user->save(); 
        
        SendVerificationMail::dispatch($user,$site_settings,$user->email); 
        return redirect()->back()->with('message', 'success');
    }
}
