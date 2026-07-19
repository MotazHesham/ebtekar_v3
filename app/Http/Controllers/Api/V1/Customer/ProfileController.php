<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Exceptions\UserFriendlyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\BeSellerRequest;
use App\Http\Requests\Api\V1\Customer\UpdateProfileRequest;
use App\Http\Resources\V1\Customer\UserResource;
use App\Http\ResponseHelper;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function info()
    {
        return ResponseHelper::returnResource(new UserResource(auth()->user()), trans('api.success.info'));
    }

    public function deviceToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required'
        ]);

        DeviceToken::updateOrCreate([
            'application' => 'customer',
            'device_token' => $request->device_token,
            'user_id' => auth()->user()->id,
        ], []);

        return ResponseHelper::returnResponse(trans('api.success.updated'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = User::find(auth()->user()->id);
        $user->update($request->validated());
        return ResponseHelper::returnResponse(trans('api.success.updated'));
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'old_password' => 'required',
            'new_password' => [
                'required',
                'min:' . (config('panel.min_password_length') ?? 8),
                'max:' . (config('panel.max_password_length') ?? 32),
            ],

        ]);
        $user = User::find(auth()->user()->id);
        if (!Hash::check($request->old_password, $user->password)) {
            throw new UserFriendlyException(trans('api.errors.oldPassword'));
        }
        $user->update(['password' => $request->new_password]);
        return ResponseHelper::returnResponse(trans('api.success.passwordUpdated'));
    }

    public function deleteAccount()
    {
        $user = User::find(auth()->user()->id);
        $user->deviceTokens()->delete();
        $user->customer()->delete();
        $user->delete();
        return ResponseHelper::returnResponse(trans('api.success.accountDeleted'));
    }
}
