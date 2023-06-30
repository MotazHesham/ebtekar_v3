<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
Use Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update_profile(Request $request){

        $this->validate($request,[
            'phone_number'=>'required|regex:/(01)[0-9]{9}/|size:11',
        ]);

        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;
        $user->save();

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
