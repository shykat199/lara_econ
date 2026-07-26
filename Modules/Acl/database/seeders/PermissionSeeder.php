<?php

namespace Modules\Acl\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Permissions grouped by module. Admin always gets all of them; other
     * roles only get what's listed for them below.
     */
    private const PERMISSIONS = [
        'products' => ['view', 'create', 'edit', 'delete'],
        'categories' => ['view', 'create', 'edit', 'delete'],
        'orders' => ['view', 'create', 'delete'],
        'users' => ['view', 'create', 'edit', 'delete'],
        'customers' => ['view', 'assign', 'reengage'],
        'roles' => ['manage'],
    ];

    /**
     * Permissions granted to non-admin roles, beyond what admin gets (which is everything).
     */
    private const ROLE_PERMISSIONS = [
        'employee' => ['customers.view', 'customers.reengage'],
        'customer' => [],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissions = [];

        foreach (self::PERMISSIONS as $module => $actions) {
            foreach ($actions as $action) {
                $allPermissions[] = Permission::firstOrCreate(['name' => "{$module}.{$action}", 'guard_name' => 'web'])->name;
            }
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($allPermissions);

        foreach (self::ROLE_PERMISSIONS as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }

        Cache::forget('spatie.permission.cache');
    }
}
