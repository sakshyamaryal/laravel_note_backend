<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Create a role
        $role = Role::create(['name' => 'admin']);

        // Create a permission
        $permission = Permission::create(['name' => 'edit notes']);

        // Assign the permission to the role
        $role->givePermissionTo($permission);
    }
}
