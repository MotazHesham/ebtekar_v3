<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['title' => 'season_access', 'type' => 'season', 'parent' => 0],
            ['title' => 'season_create', 'type' => 'season', 'parent' => 0],
            ['title' => 'season_edit', 'type' => 'season', 'parent' => 0],
            ['title' => 'season_show', 'type' => 'season', 'parent' => 0],
            ['title' => 'season_delete', 'type' => 'season', 'parent' => 0],
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
            'season_access',
            'season_create',
            'season_edit',
            'season_show',
            'season_delete',
        ];

        Permission::whereIn('title', $titles)->delete();
    }
};
