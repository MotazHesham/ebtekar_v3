<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
Use Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function dashboard(){
        $user = Auth::user();
        
        return view('frontend.dashboard',compact('user'));
    }

    public function update_profile(Request $request){

        $this->validate($request,[
            'phone_number'=>'required|regex:/(01)[0-9]{9}/|size:11',
        ]);

        $user = auth()->user();
        $seller = $user->seller;

        $user->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        $seller->update([
            'social_name' => $request->social_name, 
            'social_link' => $request->social_link, 
            'qualification' => $request->qualification, 
        ]);

        if ($request->photo != null) { 
            if ($user->photo) {
                $user->photo->delete();
            } 
            $user->addMedia($request->photo)->toMediaCollection('photo');
        }

        if ($request->identity_back != null) { 
            if ($seller->identity_back) {
                $seller->identity_back->delete();
            } 
            $seller->addMedia($request->identity_back)->toMediaCollection('identity_back');
        }

        if ($request->identity_front != null) { 
            if ($seller->identity_front) {
                $seller->identity_front->delete();
            } 
            $seller->addMedia($request->identity_front)->toMediaCollection('identity_front');
        }

        $user->save();
        $seller->save();

        toast("تم تعديل البيانات بنجاح","success");
        return redirect()->route('frontend.dashboard');
    }


    public function update_password(Request $request){

        $this->validate($request,[
            'password'=>'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $hashedPassword = $user->password;
        if(!\Hash::check($request->old_password, $hashedPassword) && $user->password != null){
            toast('Current Password Not Correct','error');
            return redirect()->route('frontend.dashboard');
        }else{
            $user->password = bcrypt($request->password);
            $user->save();
            toast('Success Changed Password','success');
            return redirect()->route('frontend.dashboard');
        }
    }

}
