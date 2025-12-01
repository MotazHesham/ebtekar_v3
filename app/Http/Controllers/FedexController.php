<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FedexController extends Controller
{
    public function webhook(Request $request)
    {
        $logger = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/fedex.log'),
            'level' => 'debug',
        ]); 
        $logger->debug('fedex:', ['request' => $request->all()]);
        return response()->json(['message' => 'Webhook received'], 200);
    }
}
