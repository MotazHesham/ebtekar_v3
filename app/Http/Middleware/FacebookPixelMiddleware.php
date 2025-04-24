<?php

namespace App\Http\Middleware;

use Closure;

class FacebookPixelMiddleware
{
    public function handle($request, Closure $next)
    {
        $site_settings = get_site_setting();
        $response = $next($request);

        if($site_settings->id != 2){
            return $response;
        }
        // Only inject pixel for HTML responses
        if (strpos($response->headers->get('Content-Type'), 'text/html') !== false) {
            $content = $response->getContent();
            $pixelCode = $this->generatePixelCode($request);
            
            // Insert pixel code before closing head tag
            if ($pos = strpos($content, '</head>')) {
                $content = substr($content, 0, $pos) . $pixelCode . substr($content, $pos);
                $response->setContent($content);
            }
        }

        return $response;
    }

    protected function generatePixelCode($request)
    {
        $pixelId = config('facebook.pixel_id');
        
        // Generate or retrieve fbp cookie
        $fbp = $request->cookie('_fbp') ?? 'fbp.' . time() . '.' . uniqid();
        
        // Set cookie if not exists
        if (!$request->cookie('_fbp')) {
            cookie()->queue('_fbp', $fbp, 60 * 24 * 90);
        }

        return view('facebook.pageView', [
            'pixelId' => $pixelId,
            'fbp' => $fbp
        ])->render();
    } 
}