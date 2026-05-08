<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['title' => 'marketer_access', 'type' => 'marketer', 'parent' => 0],
            ['title' => 'marketer_create', 'type' => 'marketer', 'parent' => 0],
            ['title' => 'marketer_edit', 'type' => 'marketer', 'parent' => 0],
            ['title' => 'marketer_show', 'type' => 'marketer', 'parent' => 0],
            ['title' => 'marketer_delete', 'type' => 'marketer', 'parent' => 0],
            ['title' => 'marketer_reports', 'type' => 'marketer', 'parent' => 0],
            ['title' => 'marketer_payout', 'type' => 'marketer', 'parent' => 0],
            ['title' => 'marketer_payout_history', 'type' => 'marketer', 'parent' => 0],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['title' => $permission['title']],
                ['type' => $permission['type'], 'parent' => $permission['parent']]
            );
        }

        $adminRole = Role::find(1);
        if ($adminRole) {
            $permissionIds = Permission::whereIn('title', array_column($permissions, 'title'))->pluck('id')->toArray();
            $adminRole->permissions()->syncWithoutDetaching($permissionIds);
        }
    }

    public function down(): void
    {
        $titles = [
            'marketer_access',
            'marketer_create',
            'marketer_edit',
            'marketer_show',
            'marketer_delete',
            'marketer_reports',
            'marketer_payout',
            'marketer_payout_history',
        ];

        Permission::whereIn('title', $titles)->delete();
    }
};
