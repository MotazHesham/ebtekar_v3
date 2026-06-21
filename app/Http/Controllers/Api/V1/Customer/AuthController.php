<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\LoginRequest;
use App\Http\Requests\Api\V1\Customer\RegisterRequest;
use App\Http\Resources\V1\Customer\UserResource;
use App\Http\ResponseHelper;
use App\Models\Customer;
use App\Models\DeviceToken;
use App\Models\PhoneVerification;
use App\Models\User;
use App\Utils\EmailUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        $request->validate([
            'device_token' => 'required'
        ]);

        $user = $request->user();

        DeviceToken::where('application', 'customer')
            ->where('device_token', $request->device_token)
            ->where('user_id', $user->id)
            ->first()?->delete();

        $user->currentAccessToken()->delete();

        return ResponseHelper::returnResponse(trans('api.success.logout'));
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('phone_number', $request->phone_or_email)->first();
        if (!$user) {
            $user = User::where('email', $request->phone_or_email)->first();
        }
        if ($user) {
            if ($user->blocked) {
                return ResponseHelper::returnNotProcessed(
                    trans('api.errors.blocked')
                );
            }
            if (!$user->verified_at) {
                $remainingSeconds = sendRegisterOtpCode($user->phone_number, 'customer', 'phone_number_verification', 'register');
                return ResponseHelper::returnResponse(
                    trans('api.errors.notVerified'),
                    [
                        'redirect' => 'register-otp',
                        'user_id' => $user->id,
                        'phone_number' => $user->phone_number,
                        'remainingSeconds' => $remainingSeconds
                    ],
                );
            }
            $token = $user->createToken('customer')->plainTextToken;
            $user->token = $token;
            return ResponseHelper::returnResource(UserResource::make($user), trans('api.success.login'));
        }
        return ResponseHelper::returnNotProcessed(trans('api.errors.loginFailed'));
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => $request->password,
            'approved' => 1,
            'user_type' => 'customer',
            'website_setting_id' => get_site_setting()->id,
        ]);
        Customer::create([
            'user_id' => $user->id
        ]);

        $remainingSeconds = sendRegisterOtpCode($user->phone_number, 'customer', 'phone_number_verification', 'register');

        return ResponseHelper::returnResponse(
            trans('api.success.register'),
            [
                'redirect' => 'register-otp',
                'user_id' => $user->id,
                'phone_number' => $user->phone_number,
                'remainingSeconds' => $remainingSeconds
            ],
        );
    }

    public function registerOtp(Request $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        $verification = PhoneVerification::where('phone', $request->phone_number)->latest()->first();

        if (!$user) {
            return ResponseHelper::returnNotFound(trans('api.errors.phoneNotExists'));
        }
        if (!$verification || $verification->expires_at < now()) {
            return ResponseHelper::returnNotProcessed(trans('api.errors.otpExpired'));
        }
        if ($verification->otp_code != $request->code) {
            return ResponseHelper::returnNotProcessed(trans('api.errors.wrongOtp'));
        }

        $user->verified_at = date(config('panel.date_format') . ' ' . config('panel.time_format'));
        $user->verified = 1;
        $user->save();
        $token = $user->createToken('customer')->plainTextToken;
        $user->token = $token;
        return ResponseHelper::returnResource(UserResource::make($user), trans('api.success.verifiedOtp'));
    }

    public function registerOtpResend(Request $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {
            return ResponseHelper::returnNotFound(trans('api.errors.phoneNotExists'));
        }

        // Check if there's an active, unexpired OTP
        $existingOtp = PhoneVerification::where('phone', $request->phone_number)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($existingOtp) {
            $remainingSeconds = now()->diffInSeconds($existingOtp->expires_at);
            return ResponseHelper::returnNotProcessed(
                trans('api.errors.alreadySentOtp'),
                ['remainingSeconds' => $remainingSeconds]
            );
        }
        $remainingSeconds = sendRegisterOtpCode($user->phone_number, 'customer', 'phone_number_verification', 'register');
        return ResponseHelper::returnResponse(
            trans('api.success.sentOtp'),
            ['remainingSeconds' => $remainingSeconds],
        );
    }
}
