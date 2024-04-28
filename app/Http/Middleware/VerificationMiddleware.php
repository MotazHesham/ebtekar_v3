<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\User;
use App\Notifications\VerifyUserNotification;
use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class VerificationMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if (! auth()->user()->verified) {
                $site_settings = get_site_setting(); 
                $user = User::find(auth()->id());
                $token     = Str::random(64); 
                $user->verification_token = $token;
                $user->save(); 

                $user->notify(new VerifyUserNotification($user,$site_settings->email,$site_settings->site_name));

                auth()->logout();

                return redirect()->route('login')->with('message', trans('global.verifyYourEmail'));
            }
        }

        return $next($request);
    }
}
