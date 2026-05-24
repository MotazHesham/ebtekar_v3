<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ShippingPermissionRoleTableSeeder extends Seeder
{
    /**
     * Maps shipping operational roles to permission titles.
     *
     * @var array<string, string[]>
     */
    protected array $rolePermissions = [
        'Shipping Partner' => [
            'delivery_order_access',
            'delivery_order_show',
            'delivery_order_mark_delivered',
            'delivery_scan_receive',
            'delivery_reports_access',
            'delivery_export',
        ],
        'Receiving Clerk' => [
            'delivery_order_access',
            'delivery_order_show',
            'delivery_scan_receive',
        ],
        'Courier' => [
            'delivery_order_access',
            'delivery_order_show',
            'delivery_order_edit',
            'delivery_order_mark_delivered',
            'delivery_return_access',
        ],
        'Dispatcher' => [
            'delivery_managment_access',
            'delivery_order_access',
            'delivery_order_show',
            'delivery_order_edit',
            'delivery_order_mark_delivered',
            'delivery_order_status_override',
            'delivery_order_revert_handoff',
            'delivery_order_cancel_delivered',
            'delivery_order_cancel_return',
            'delivery_return_admin_manage',
            'delivery_export',
            'delivery_assign_courier',
            'delivery_settlement_access',
            'delivery_return_access',
            'delivery_notifications_access',
        ],
        'Delivery Customer Service' => [
            'delivery_order_access',
            'delivery_order_show',
            'delivery_return_access',
        ],
    ];

    public function run(): void
    {
        foreach ($this->rolePermissions as $roleTitle => $permissionTitles) {
            $role = Role::where('title', $roleTitle)->first();
            if (! $role) {
                continue;
            }

            $permissionIds = Permission::whereIn('title', $permissionTitles)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }
    }
}
