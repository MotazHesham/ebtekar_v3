<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        // $current_user_ip =  '102.177.185.0'; //emarats
        // $current_user_ip =  '78.154.192.0'; //Kuwit
        // $current_user_ip =  '142.247.0.0'; //Saudi
        $current_user_ip = request()->ip();
        if (Session::get('ip') !== $current_user_ip) {

            Session::put('ip', $current_user_ip);
            $user_info_by_ip = Location::get($current_user_ip);

            // Set default values if no info found
            $country_code = $user_info_by_ip->countryCode ?? 'EG'; 
            $state = $user_info_by_ip->regionName ?? 'Cairo'; 

            // Store country and state in session
            Session::put('country_code', $country_code);
            Session::put('state_by_ip', $state);
        } 
        
        // Get the country code from the session (default to 'EG' if not set)
        $country_code = Session::get('country_code', 'EG'); 

        // Retrieve the currency based on the country code
        $currency = Currency::where('code', $country_code)->first();

        // Check if currency exists, if not, fallback to a default currency
        if ($currency) {
            Session::put('currency', $currency);
        } else {
            // Fallback: Set a default currency, e.g., EGP
            $defaultCurrency = Currency::where('code', 'EG')->first();
            Session::put('currency', $defaultCurrency);
        } 

        // set the currency rates
        setCurrencyRate();
        
        return $next($request);
    }
}
