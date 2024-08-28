<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\Pages\UserController;


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
Route::post('notes', [NotesController::class, 'addNotes']);
Route::get('notes/{id}', [NotesController::class, 'getUserWiseNotes']);
Route::put('notes/{id}', [NotesController::class, 'updateNotes']);
Route::get('users', [UserController::class, 'getUsers']);
Route::get('rolesAndPermission', [UserController::class, 'getRoleAndPermission']);

Route::get('users', [UserController::class, 'getUsers']);
Route::get('rolesAndPermission', [UserController::class, 'getRoleAndPermission']);
Route::post('updatePermission', [UserController::class, 'updatePermission']);
Route::post('updateUserRole', [UserController::class, 'updateUserRole']);
Route::post('createRole', [UserController::class, 'createRole']);

Route::middleware('auth:api')->get('user', [AuthController::class, 'user']);



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
