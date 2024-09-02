<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;


class AuthController extends Controller
{
    // Register new users
    public function register(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['erors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('appToken')->accessToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    // Login existing users
    // public function login(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|string|email|max:255',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }        
        
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();
    //         $token = $user->createToken('appToken')->accessToken;
    //         $userRoleAndPermission = array();

    //         if ($user->role_id) {
    //             $userRoleAndPermission = DB::table('users')
    //             ->join('roles', 'roles.id', '=', 'users.role_id')
    //             ->join('role_has_permissions', 'users.role_id', '=', 'roles.id')
    //             ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
    //             ->where('users.id', $user->id)
    //             ->select('roles.name as userrole', 'permissions.name as permission')
    //             ->get();
    //         }else {
    //             $userRoleAndPermission[0]  = array(
    //                 'userrole'=>'',
    //             );
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'token' => $token,
    //             'user' => $user,
    //             'userRoleAndPermission' => $userRoleAndPermission,
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to authenticate.',
    //         ], 401);
    //     }
    // }
    public function login(Request $request)
    {
        // dd($request);
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }        

        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $token = $user->createToken('appToken')->accessToken;


            // Retrieve roles and permissions using Spatie's methods
            $roles = $user->roles->pluck('name'); // Get role names
            $permissions = $user->getAllPermissions()->pluck('name'); // Get permission names

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
                'roles' => $roles,
                'permissions' => $permissions,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate.',
            ], 401);
        }
    }

}
