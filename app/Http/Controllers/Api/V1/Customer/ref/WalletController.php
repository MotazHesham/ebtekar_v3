<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\WalletTransactionRequest;
use App\Http\Resources\V1\Customer\WalletResource;
use App\Http\Resources\V1\Customer\WalletTransactionResource;
use App\Http\ResponseHelper;
use App\Models\CustomerPoint;
use App\Models\PaymentMethod;
use App\Models\WalletTransaction; 
use App\Services\PaymentService;
class WalletController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {}
    public function info()
    {
        $wallet = request()->user()->userWallet();
        return ResponseHelper::returnResource(new WalletResource($wallet));
    }

    public function transactions()
    {
        $transactions = WalletTransaction::where('user_id', request()->user()->id)
            ->with('model')
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return ResponseHelper::returnResource(WalletTransactionResource::collection($transactions));
    }

    public function charge(WalletTransactionRequest $request)
    {
        $wallet = request()->user()->userWallet();
        $paymentMethod = PaymentMethod::find($request->payment_method_id);
        $transaction = WalletTransaction::create([
            'user_id' => request()->user()->id,
            'wallet_id' => $wallet->id, 
            'type' => 'charge',
            'amount' => $request->amount, 
            'payment_method' => strtolower($paymentMethod->name),
            'payment_status' => 'paid',
        ]);
        $wallet->wallet_balance += $request->amount;
        $wallet->save();
        return ResponseHelper::returnResponse(trans('api.success.success'));

        // TODO: Add payment service
        // return $this->paymentService->processPayment($paymentMethod, 'wallet', $transaction->id);
    }

    public function changePoints()
    {
        $wallet = request()->user()->userWallet();
        if($wallet->points < 1){
            return ResponseHelper::returnNotProcessed(trans('api.errors.pointsNotEnough'));
        }
        $customerPoints = CustomerPoint::where('user_id', request()->user()->id)
            ->where('converted', 0)
            ->where('refunded', 0)
            ->get();
        foreach($customerPoints as $customerPoint){
            $customerPoint->converted = 1;
            $customerPoint->converted_amount = calculate_points_to_money($customerPoint->points);
            $customerPoint->save();
        }
        $convertedMoney = calculate_points_to_money($wallet->points);
        $wallet->points = 0;
        $wallet->wallet_balance += $convertedMoney;
        $wallet->save();

        WalletTransaction::create([
            'user_id' => request()->user()->id,
            'wallet_id' => $wallet->id,
            'type' => 'change_points',
            'amount' => $convertedMoney,
            'payment_status' => 'paid',
        ]);
        return ResponseHelper::returnResponse(trans('api.success.success'));
    }
}