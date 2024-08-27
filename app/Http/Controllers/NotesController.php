<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotesController extends Controller
{
    //
    public function addNotes(Request $request)
    {

        try {

            $userid = $request->user_id;
            $text = $request->text;
            $title = $request->title;

            $create_note = Notes::create([
                'user_id' => $userid,
                'text' => $text,
                'title' => $title,
            ]);

            if ($create_note) {
                return response()->json(['success' => true, 'data' => $create_note], 201);
            }

            return response()->json(['success' => false, 'data' => $create_note], 400);
        } catch (\Exception $th) {

            return response()->json(['success' => false, 'data' => $th], 400);
        }
    }

    public function getUserWiseNotes(Request $request, $id)
    {
        try {
            $getnotes =
                DB::table('notes')
                ->where('notes.user_id', $id)
                ->select('*')
                ->get();
            return response()->json(['success' => true, 'data' => $getnotes], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateNotes(Request $request, $id)
    {
        try {
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
