<?php

namespace App\Http\Controllers;

use App\Services\FacebookService;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function trackEvent(Request $request)
    {
        $validated = $request->validate([
            'event' => 'required|string',
            'data' => 'required|array',
            'fbp' => 'nullable|string',
            'fbc' => 'nullable|string',
        ]);

        $facebookService = new FacebookService();
        
        $userData = [
            'fbp' => $validated['fbp'] ?? $request->cookie('_fbp'),
            'fbc' => $validated['fbc'] ?? $request->cookie('_fbc'),
            'external_id' => auth()->check() ? auth()->id() : null,
        ];

        $facebookService->sendEventFromJs(
            $validated['event'], 
            $userData, 
            $validated['data']
        );

        return response()->json(['success' => true]);
    }
}