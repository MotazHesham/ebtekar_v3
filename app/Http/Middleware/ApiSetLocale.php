<?php

namespace App\Http\Middleware;

use Closure;

class ApiSetLocale
{
    public function handle($request, Closure $next)
    {
        if (request()->header('language')) {
            $language = request()->header('language');
        } elseif (config('panel.primary_language')) {
            $language = config('panel.primary_language');
        }

        if (isset($language)) {
            app()->setLocale($language);
        }
        return $next($request);
    }
}
