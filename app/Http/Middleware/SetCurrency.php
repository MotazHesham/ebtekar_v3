<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Stevebauman\Location\Facades\Location;

class SetCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    { 
        
        if(\Request::route()->getName() == 'updating_website'){
            return $next($request);
        }

        if(request('password')){
            session()->put('password',request('password')); 
            if(request('password') != '1219'){ 
                return redirect()->route('updating_website');
            }
        }elseif(session('password')){
            if(session('password') != '1219'){ 
                return redirect()->route('updating_website');
            }
        }else{
            if(\Request::route()->getName() == 'updating_website'){
                return $next($request);
            }else{
                return redirect()->route('updating_website');
            }
        }

        $current_user_ip = request()->ip();
        // $current_user_ip =  '102.177.185.0'; //emarats
        // $current_user_ip =  '78.154.192.0'; //Kuwit
        // $current_user_ip =  '142.247.0.0'; //Saudi
        if(Session::get('ip') == null || Session::get('ip') != $current_user_ip){
            Session::put('ip',$current_user_ip);
            $user_info_by_ip = Location::get($current_user_ip);
            $country_code = $user_info_by_ip->countryCode ?? 'EG'; 
            Session::put('country_code',$country_code);
        }
        
        $country_code = Session::get('country_code') ?? 'EG'; 
        $currency = Currency::where('code',$country_code)->first();
        Session::put('currency',$currency);
        
        return $next($request);
    }
}
