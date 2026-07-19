<?php

namespace App\Http\Controllers\Api\V1\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\UserResource;
use App\Http\ResponseHelper;
use App\Models\Order;
use App\Models\User;
use App\Utils\OrderUtility;

class CashController extends Controller
{
    public function pay($paymentType, $paymentId)
    {
        if ($paymentType == 'order') {
            $order = Order::withoutGlobalScope('completed')->find($paymentId);
            app(OrderUtility::class)->handlePaymentSuccess($order, 'unpaid');
        } else {
            $message = trans('api.errors.paymentType');
            return ResponseHelper::returnNotProcessed($message);
        }

        $user = User::find($order->user_id);
        $token = $user->createToken('customer')->plainTextToken;
        $user->token = $token;
        $message = trans('api.success.paymentSuccess');
        return ResponseHelper::returnResponse($message, [
            'is_redirect' => false,
            'redirect_url' => null,
            'confirmation_info_message' => 'شكرا لاختيارك ابتكار ستور , سنقوم بمراجعة الطلب والتوصيل في خلال 72 ساعة',
            'expected_delivery_date' => now()->addDays(3)->toDateString(),
            'order_id' => $order->id,
            'order_num' => $order->order_num,
            'user' => new UserResource($user),
        ]);
    }
}
