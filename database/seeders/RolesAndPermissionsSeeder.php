<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define roles and permissions
        $roles = ['admin', 'user'];
        $permissions = ['add', 'edit', 'delete'];

        // Create and store roles
        foreach ($roles as $roleName) {
            $role = Role::create(['name' => $roleName]);

            // Assign permissions to each role
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);

                // Assign permission to the role
                $role->givePermissionTo($permission);
            }
        }

        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $userRole->revokePermissionTo('edit');
        }
    }
}
