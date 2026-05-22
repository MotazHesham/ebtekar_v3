<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Allows internal staff and shipping operational roles into the shipping admin area.
 */
class ShippingPortal
{
    public const PORTAL_USER_TYPES = [
        'staff',
        'admin',
        'shipping_partner',
        'courier',
        'delivery_man',
        'dispatcher',
        'receiving_clerk',
        'delivery_cs',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->user_type, self::PORTAL_USER_TYPES, true)) {
            return redirect()->route('frontend.dashboard');
        }

        return $next($request);
    }
}
