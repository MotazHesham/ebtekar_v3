<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'                 => 1,
                'name'               => 'Admin',
                'email'              => 'admin@admin.com',
                'password'           => bcrypt('password'),
                'remember_token'     => null,
                'approved'           => 1,
                'verified'           => 1,
                'user_type'           => 'staff',
                'verified_at'        => '2023-06-05 08:20:31',
                'providerid'         => '',
                'phone_number'       => '',
                'wasla_token'        => '',
                'device_token'       => '',
                'verification_token' => '',
            ],
        ];

        User::insert($users);
    }
}
