<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $titles = [
        'inventory_access',
        'inventory_create',
        'inventory_edit',
        'inventory_adjust',
        'inventory_shopify',
    ];

    public function up(): void
    {
        $ids = [];
        foreach ($this->titles as $title) {
            $permission = Permission::firstOrCreate(
                ['title' => $title],
                ['type' => 'inventory', 'parent' => 0]
            );
            $ids[] = $permission->id;
        }

        $role = Role::where('title', 'Admin')->first();
        if (! $role) {
            return;
        }

        $existing = $role->permissions()->whereIn('permissions.id', $ids)->pluck('permissions.id')->all();
        $missing = array_diff($ids, $existing);
        if ($missing !== []) {
            $role->permissions()->attach($missing);
        }
    }

    public function down(): void
    {
        $permissions = Permission::whereIn('title', $this->titles)->get();
        if ($permissions->isEmpty()) {
            return;
        }

        DB::table('permission_role')->whereIn('permission_id', $permissions->pluck('id'))->delete();
        Permission::whereIn('id', $permissions->pluck('id'))->delete();
    }
};
