<?php

namespace App\Http\Middleware;

use App\Models\WebsiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class AccessEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $site_settings = WebsiteSetting::first();  
        if(Cookie::has('access_employee') && $site_settings->employee_password == Cookie::get('access_employee')){
            return $next($request);
        }else{
            toast('قم بتسجيل الدخول مرة أخري لقائمة السلف','warning');
            return redirect()->route('admin.home');
        }
    }
}
