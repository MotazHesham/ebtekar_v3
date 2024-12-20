<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['required', 'regex:' . config('panel.phone_number_format'),'size:' . config('panel.phone_number_size')], 
        ]);
    }

        /**
         * Create a new user instance after a valid registration.
        *
        * @param  array  $data
        * @return \App\Models\User
        */
        protected function create(array $data)
        {
            
            $site_settings = get_site_setting();
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone_number'    => $data['phone_number'],
                'user_type'    => 'customer',
                'approved'    => 1,
                'verified'    => 1,
                'password' => Hash::make($data['password']),
                'website_setting_id' => $site_settings->id,
            ]);

            Customer::create([
                'user_id' => $user->id
            ]);
            
            return $user;
        }
}
