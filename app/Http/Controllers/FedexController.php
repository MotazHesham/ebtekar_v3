<?php

namespace App\Http\Controllers;

use App\Models\ReceiptSocial;
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
        $receipt_social = ReceiptSocial::where('order_num',$request->Shipper_Ref)->first();
        if($receipt_social){
            $receipt_social->update([
                'tracking_number' => $request->tracking_number, 
                'status_code' => $request->status_code,
                'status_description' => $request->status_description,
            ]);
            if($request->status_code == 'POD' && $receipt_social->done == 0){
                $receipt_social->update([
                    'done' => 1,
                ]);
            }
        }else{
            $logger->debug('fedex:', ['error' => 'Receipt social not found']);
        }
        return response()->json(['message' => 'Webhook received'], 200);
    }
}
