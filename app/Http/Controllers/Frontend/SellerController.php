<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function beseller(){ 
        $sellers = Seller::with('user')->get()->take(9);
        return view('frontend.beseller',compact('sellers'));
    }

    public function register(Request $request){
        $request->validate([ 
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],  
            'phone_number' => ['required', 'regex:' . config('panel.phone_number_format'),'size:' . config('panel.phone_number_size')],  
            'social_name'     => ['required', 'string', 'max:255'],
            'social_link'     => ['required', 'string', 'max:255'], 
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'user_type' => 'seller',
            'approved' => 0,
        ]);


        $random_string = generateRandomString();
        
        Seller::create([
            'user_id' => $user->id,
            'seller_type' => 'seller',
            'seller_code' => $user->id . $random_string,   
            'qualification' => $request->qualification,
            'social_name' => $request->social_name,
            'social_link' => $request->social_link,
        ]);
        Auth::login($user);
        alert('تم التسجيل بنجاح','يرجي تأكيد البريد الألكتروني وسيتم مراجعة بياناتك من الأدارة','success');
        return redirect()->route('frontend.dashboard');
    }
}
