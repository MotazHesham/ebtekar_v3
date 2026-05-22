<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class ShippingRolesTableSeeder extends Seeder
{
    public function run(): void
    {
        foreach (array_unique(array_values(\App\Services\ShippingRoleAssigner::USER_TYPE_TO_ROLE)) as $title) {
            Role::updateOrCreate(
                ['title' => $title],
                ['title' => $title]
            );
        }
    }
}
