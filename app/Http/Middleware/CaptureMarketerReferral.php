<?php

namespace App\Http\Middleware;

use App\Services\MarketerAttributionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureMarketerReferral
{
    protected $attributionService;

    public function __construct(MarketerAttributionService $attributionService)
    {
        $this->attributionService = $attributionService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('get')) {
            $this->attributionService->captureVisit($request, get_site_setting());
        }

        $response = $next($request);

        $refCode = $request->query('ref') ?: $request->session()->get(MarketerAttributionService::REF_COOKIE);
        if ($refCode) {
            $minutes = $this->attributionService->resolveCookieLifetimeMinutes(get_site_setting());
            cookie()->queue(MarketerAttributionService::REF_COOKIE, $refCode, $minutes);
        }

        $visitorId = $request->attributes->get('marketer_cookie_id') ?: $request->cookie(MarketerAttributionService::VISITOR_COOKIE);
        if ($visitorId) {
            cookie()->queue(MarketerAttributionService::VISITOR_COOKIE, $visitorId, 60 * 24 * 365);
        }

        return $response;
    }
}
