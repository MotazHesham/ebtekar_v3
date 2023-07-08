<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Designer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignerController extends Controller
{
    public function bedesigner(){ 
        $designers = Designer::with('user')->get()->take(9);
        return view('frontend.be-designer',compact('designers'));
    }

    public function register(Request $request){
        $request->validate([ 
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],  
            'phone_number' => ['required', 'regex:' . config('panel.phone_number_format'),'size:' . config('panel.phone_number_size')],   
            'store_name'     => ['required', 'string', 'max:255','unique:designers'],
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'user_type' => 'designer',
            'approved' => 0,
        ]); 
        
        Designer::create([
            'user_id' => $user->id,
            'store_name' => $request->store_name, 
        ]);
        
        Auth::login($user);
        alert('تم التسجيل بنجاح','يرجي تأكيد البريد الألكتروني وسيتم مراجعة بياناتك من الأدارة','success');
        return redirect()->route('frontend.dashboard');
    }
}
