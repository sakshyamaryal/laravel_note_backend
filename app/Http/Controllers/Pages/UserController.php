<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role; 
use Spatie\Permission\Models\Permission; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::with('roles')->get(); 
        $users->each(function($user) {
            if ($user->roles->isNotEmpty()) {
                $user->userrole = $user->roles->first()->name;
            } else {
                $user->userrole = 'N/A';
            }
        });
    

        return response()->json(['data' => $users]);
    }

    public function getRoleAndPermission()
    {
        $roles = Role::with('permissions')->get();

        return response()->json(['data' => $roles]);
    }

    public function updatePermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission' => 'required|string',
            'is_checked' => 'required|boolean'
        ]);

        $role = Role::find($request->input('role_id'));

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $permission = Permission::where('name', $request->input('permission'))->first();
        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        if ($request->input('is_checked')) {
            $role->givePermissionTo($permission);
        } else {
            $role->revokePermissionTo($permission);
        }

        return response()->json(['success' => true]);
    }

    public function updateUserRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::find($request->input('user_id'));

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $role = Role::find($request->input('role_id'));

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $user->roles()->sync([$role->id]);

        return response()->json(['success' => true]);
    }

    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->permissions = array();
        return response()->json(['data' => $role], 201);
    }
}
