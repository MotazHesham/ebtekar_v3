<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ResetPasswordRequest;
use App\Http\ResponseHelper;
use App\Models\PhoneVerification;
use App\Models\User;
use App\Utils\SmsUtility;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function forgetpassword(Request $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return ResponseHelper::returnNotFound(trans('api.errors.phoneNotExists'));
        }

        $random_number = randomOtpCode();

        $phoneVerification = PhoneVerification::updateOrCreate(
            ['phone' => $user->phone_number],
            [
                'otp_code' => $random_number,
                'expires_at' => now()->addMinutes(otpExpiryMinutes()),
            ]
        );
        if ($user && $phoneVerification) {
            $msg = trans('api.success.sendOtp') . $random_number;
            SmsUtility::password_reset($user->phone_number, $random_number);
        }

        return ResponseHelper::returnResponse(trans('api.success.sentOtp'), [
            'phone_number' => $user->phone_number,
            'remainingSeconds' => now()->diffInSeconds($phoneVerification->expires_at),
        ]);
    }
    public function forgetpasswordOtp(Request $request)
    {

        $phoneVerification = PhoneVerification::where('phone', $request->phone_number)->first();

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return ResponseHelper::returnNotFound(trans('api.errors.phoneNotExists'));
        }

        if (!$phoneVerification) {
            return ResponseHelper::returnNotFound(trans('api.errors.phoneNotExists'));
        } else {
            if ($phoneVerification->otp_code != $request->code) {
                return ResponseHelper::returnNotProcessed(trans('api.errors.wrongOtp'));
            }
        }

        if (Carbon::parse($phoneVerification->updated_at)->addMinutes(720)->isPast()) {
            $phoneVerification->delete();
            return ResponseHelper::returnNotProcessed(trans('api.errors.otpExpired'));
        }

        return ResponseHelper::returnResponse(trans('api.success.verifiedOtp'));
    }

    public function forgetpasswordReset(ResetPasswordRequest $request)
    {

        $phoneVerification = PhoneVerification::where('phone', $request->phone_number)->first();

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return ResponseHelper::returnNotFound(trans('api.errors.phoneNotExists'));
        }

        if (!$phoneVerification) {
            return ResponseHelper::returnNotFound(trans('api.errors.otpExpired'));
        } else {
            if ($phoneVerification->otp_code != $request->code) {
                return ResponseHelper::returnNotProcessed(trans('api.errors.wrongOtp'));
            }
        }

        if (Carbon::parse($phoneVerification->updated_at)->addMinutes(720)->isPast()) {
            $phoneVerification->delete();
            return ResponseHelper::returnNotProcessed(trans('api.errors.otpExpired'));
        }

        $user->password = $request->password;
        $user->save();

        $phoneVerification->delete();

        return ResponseHelper::returnResponse(trans('api.success.passwordChanged'));
    }
}
