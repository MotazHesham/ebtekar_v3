<?php

namespace App\Http\Middleware;

use App\Http\ResponseHelper;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomerAuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('sanctum');

        if (!$guard->check()) {
            throw new AuthenticationException(trans('api.errors.unauthenticated'));
        }

        $customer = $guard->user();
        if ($customer && $customer->block) {
            $request->user()->currentAccessToken()->delete();
            throw new AuthenticationException(trans('api.errors.blocked'));
        }

        return $next($request);
    }
}
