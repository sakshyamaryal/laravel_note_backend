<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class NotesController extends Controller
{
    //
    public function addNotes(Request $request)
    {

      
        // if (!auth()->user()->can('edit')) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        try {
            $userid = $request->user_id;
            $userrole = User::findOrFail($userid);
            $permissions = $userrole->getPermissionsViaRoles();
            $roles_per_arr = array();

            foreach($permissions as $user_per => $ind){
                array_push($roles_per_arr,$ind->name);
            }

            if(!in_array('add', $roles_per_arr)){
                return response()->json(['success' => false, 'data' => 'permissionismissing', 'roles_per_arr' => $roles_per_arr, 'msg'=>'Couldnot add Data beause User Doesnot have Add Permission'], 200);
            }

            $text = $request->text;
            $title = $request->title;

            $create_note = Notes::create([
                'user_id' => $userid,
                'text' => $text,
                'title' => $title,
            ]);

            if ($create_note) {
                return response()->json(['success' => true, 'data' => $create_note, 'roles_per_arr' => $roles_per_arr], 201);
            }

            return response()->json(['success' => false, 'data' => $create_note], 400);
        } catch (\Exception $th) {

            return response()->json(['success' => false, 'data' => $th], 400);

        }
    }

    public function getUserWiseNotes(Request $request, $id)
    {
        try {

            $userrole = User::findOrFail($id);
            $isadminuser = $userrole->hasRole('admin');

            if ($isadminuser) {
                $getnotes =
                DB::table('notes')
                ->select('*')
                ->get();
            }else{
                $getnotes =
                DB::table('notes')
                ->where('notes.user_id', $id)
                ->select('*')
                ->get();
            }
            
            return response()->json(['success' => true, 'data' => $getnotes, 'role'=>$isadminuser], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateNotes(Request $request, $id)
    {
        try {
            $userid = $request->user_id;
            $userrole = User::findOrFail($userid);
            $permissions = $userrole->getPermissionsViaRoles();
            $roles_per_arr = array();

            foreach($permissions as $user_per => $ind){
                array_push($roles_per_arr,$ind->name);
            }

            if(!in_array('edit', $roles_per_arr)){
                return response()->json(['success' => false, 'data' => 'permissionismissing', 'roles_per_arr' => $roles_per_arr, 'msg'=>'Couldnot add Data beause User Doesnot have Add Permission'], 200);
            }

            $note = Notes::findOrFail($id);

            if (!$note) {
                return response()->json(['success' => false, 'errormsg' => 'notes not found '], 404);
            }

            $update = $note->update($request->all());

            if ($update) {
                return response()->json(['success' => true, 'data' => $request->all()], 200);
            }

            return response()->json(['success' => false, 'errormsg' => 'notes not updated '], 404);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'errormsg' => $th]);
        }
    }
}
