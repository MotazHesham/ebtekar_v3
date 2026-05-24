<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class ShippingExtendedPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['title' => 'delivery_order_mark_delivered', 'type' => 'delivery_order'],
            ['title' => 'delivery_order_status_override', 'type' => 'delivery_order'],
            ['title' => 'delivery_order_revert_handoff', 'type' => 'delivery_order'],
            ['title' => 'delivery_order_cancel_delivered', 'type' => 'delivery_order'],
            ['title' => 'delivery_order_cancel_return', 'type' => 'delivery_order'],
            ['title' => 'delivery_return_admin_manage', 'type' => 'delivery_order'],
            ['title' => 'delivery_export', 'type' => 'delivery_order'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['title' => $permission['title']],
                ['type' => $permission['type'], 'parent' => 0]
            );
        }
    }
}
