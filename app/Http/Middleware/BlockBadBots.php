<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockBadBots
{
    /**
     * List of bad bots to block.
     */
    protected $badBots = [
        'SemrushBot', 'Barkrowler', 'bingbot', 'PetalBot', 'facebookexternalhit',
        'DotBot', 'CCBot', 'YandexBot', 'SeznamBot', 'crawler', 'spider',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = strtolower($request->header('User-Agent'));

        foreach ($this->badBots as $bot) {
            if (stripos($userAgent, strtolower($bot)) !== false) {
                abort(403, "Access Denied: Bot Detected");
            }
        }

        return $next($request);
    }
}
