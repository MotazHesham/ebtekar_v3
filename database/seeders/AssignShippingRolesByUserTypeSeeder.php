<?php

namespace Database\Seeders;

use App\Services\ShippingRoleAssigner;
use Illuminate\Database\Seeder;

class AssignShippingRolesByUserTypeSeeder extends Seeder
{
    public function run(): void
    {
        $count = ShippingRoleAssigner::assignAllUsers();

        $this->command?->info("Assigned shipping roles to {$count} user(s) by user_type.");
    }
}
