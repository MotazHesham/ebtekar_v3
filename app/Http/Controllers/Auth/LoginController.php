<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function redirectToProvider($provider)
    {
        Session::put('link', url()->previous());
        return Socialite::driver($provider)->redirect();
    }

    
    public function handleProviderCallback(Request $request, $provider)
    { 
        try {
            if ($provider == 'twitter') {
                $user = Socialite::driver('twitter')->user();
            } else {
                $user = Socialite::driver($provider)->stateless()->user();
            }
        } catch (\Exception $e) {
            alert("Something Went wrong. Please try again.",'','error');
            return redirect()->route('login');
        } 

        // check if they're an existing user
        $existingUser = User::where('providerid', $user->id)->orWhere('email', $user->email)->first();

        if ($existingUser) {
            // log them in
            if($existingUser->user_type == 'customer'){
                auth()->login($existingUser, true);
            }else{
                alert(__('This Account Exisit As ' . $existingUser->user_type . ' Account Use Diffrent Account To Register'),'','error');
                return redirect()->route('login');
            }
        } else {
            // create a new user
            $newUser = new User;
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->email_verified_at = date('Y-m-d H:m:s');
            $newUser->provider_id = $user->id;
            $newUser->user_type = 'customer'; 
            $newUser->save(); 

            auth()->login($newUser, true);
        }
        if (Session::get('link') != null) {
            return redirect(Session::get('link'));
        } else {
            return redirect()->route('home');
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */ 

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
