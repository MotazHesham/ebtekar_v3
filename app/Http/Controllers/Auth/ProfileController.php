<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WaslaController; 
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function wasla_logout()
    {   
        $user = Auth::user();
        $user->wasla_token = null;
        $user->wasla_company_id = null;
        $user->save();
        alert('تم تسجل الخروج بنجاح من واصلة','','success'); 
        return redirect()->route('profile.password.edit');
    } 

    public function wasla_login(Request $request)
    { 
        $waslaController = new WaslaController;
        $response = $waslaController->login($request); 
        if($response){ 
            alert('تم تسجل الدخول بنجاح لواصلة','','success');
        }else{ 
            alert('invalid username or password','','error');
        }
        return redirect()->route('profile.password.edit');
    } 

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();

        $user->update($request->validated());
        
        if ($request->input('photo', false)) {
            if (! $user->photo || $request->input('photo') !== $user->photo->file_name) {
                if ($user->photo) {
                    $user->photo->delete();
                }
                $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($user->photo) {
            $user->photo->delete();
        }

        return redirect()->route('profile.password.edit')->with('message', __('global.update_profile_success'));
    } 
}
