<?php

namespace App\Http\Middleware;

use Closure; 

class VerificationMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if (! auth()->user()->verified) { 
                $email = auth()->user()->email;
                auth()->logout();

                return redirect()->route('user.verify',['email' => $email]);
            }
        }

        return $next($request);
    }
}
