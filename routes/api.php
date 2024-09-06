<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\Pages\UserController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::get('rolesAndPermission', [UserController::class, 'getRoleAndPermission']);

// Route::get('rolesAndPermission', [UserController::class, 'getRoleAndPermission']);

Route::middleware('auth:api')->group(function ()  {
    Route::get('users', [UserController::class, 'getUsers']);
    Route::get('rolesAndPermission', [UserController::class, 'getRoleAndPermission']);
    Route::post('updateUserRole', [UserController::class, 'updateUserRole']);
    Route::post('createRole', [UserController::class, 'createRole']);
    Route::post('updatePermission', [UserController::class, 'updatePermission']);

    Route::get('notes/{id}', [NotesController::class, 'getUserWiseNotes']);
    Route::post('notes', [NotesController::class, 'addNotes']);
    Route::put('notes/{id}', [NotesController::class, 'updateNotes']);
    Route::delete('notes/{id}/{user_id}', [NotesController::class, 'delete']);    
});