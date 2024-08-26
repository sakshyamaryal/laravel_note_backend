<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    //
    public function addNotes(Request $request){

        try {

            $userid = 1;
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
}
