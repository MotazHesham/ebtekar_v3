<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            ShippingRolesTableSeeder::class,
            ShippingPermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            AssignShippingRolesByUserTypeSeeder::class,
            TaskStatusTableSeeder::class,
            WebsiteSettingTableSeeder::class,
            CurrencyTableSeeder::class,
        ]);
    }
}
