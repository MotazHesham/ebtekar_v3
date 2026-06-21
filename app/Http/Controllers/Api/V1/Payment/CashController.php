<?php

namespace App\Http\Controllers\Api\V1\Payment;

use App\Http\Controllers\Controller;
use App\Http\ResponseHelper;
use App\Models\AdPayment;
use App\Utils\AdPaymentUtility;

class CashController extends Controller
{
    public function pay($paymentType, $paymentId)
    {
        if ($paymentType == 'adPayment') {
            $adPayment = AdPayment::find($paymentId);
            app(AdPaymentUtility::class)->handlePaymentSuccess($adPayment);
        } else {
            $message = trans('api.errors.paymentType');
            if (request()->is('api/*')) {
                return ResponseHelper::returnResponse($message, null, false, '001');
            } else {
                toast($message, 'error');
                return redirect()->route('frontend.home');
            }
        }

        $message = trans('api.success.paymentSuccess');
        if (request()->is('api/*')) {
            return ResponseHelper::returnResponse($message);
        } else {
            toast($message, 'success');
            return redirect()->route('frontend.home');
        }
    }
}
