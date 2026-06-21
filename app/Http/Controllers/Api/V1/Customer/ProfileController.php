<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Exceptions\UserFriendlyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\BeSellerRequest;
use App\Http\Requests\Api\V1\Customer\UpdateProfileRequest; 
use App\Http\Resources\V1\Customer\UserResource;
use App\Http\ResponseHelper; 
use App\Services\ProfileService; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Log; 

class ProfileController extends Controller
{ 
    public function __construct(
        protected ProfileService $profileService
    ) { }

    public function info(){
        return ResponseHelper::returnResource(new UserResource(auth()->user()), trans('api.success.info'));
    }

    public function deviceToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required'
        ]);

        $this->profileService->updateDeviceToken($request->device_token, 'customer', auth()->user());

        return ResponseHelper::returnResponse( trans('api.success.updated'));
    }

    public function update(UpdateProfileRequest $request){
        return $this->profileService->updateProfile($request, auth()->user());
    }

    public function updatePassword(Request $request){
        $data = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        $this->profileService->updatePassword($data, auth()->user());
        return ResponseHelper::returnResponse(trans('api.success.passwordUpdated'));
    }

    public function deleteAccount()
    {
        $this->profileService->deleteUser(auth()->user());
        return ResponseHelper::returnResponse( trans('api.success.accountDeleted'));
    }

    public function beSeller(BeSellerRequest $request)
    {
        $user = manuallyCheckAuthUser(request()->bearerToken());
        $this->profileService->beSeller($request, $user);
        return ResponseHelper::returnResponse( trans('api.success.requestSent'));
    }
}
