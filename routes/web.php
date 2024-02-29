<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/registration', [AuthController::class, 'printRegForm']);
Route::post('/registration', [AuthController::class, 'registration']);
Route::get('/login', [AuthController::class, 'printLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/users', [UserController::class, 'getUsersList']);
Route::get('/create', [UserController::class, 'printCreateForm']);
Route::post('/create', [UserController::class, 'create']);

Route::get('/status/{id}', [UserController::class, 'printStatusForm']);
Route::post('/status/{id}', [UserController::class, 'setNewStatus']);
Route::get('/edit/{id}', [UserController::class, 'printEditForm']);
Route::post('/edit/{id}', [UserController::class, 'setNewData']);
Route::get('/security/{id}', [UserController::class, 'printSecurityForm']);
Route::post('/security/{id}', [UserController::class, 'setNewSecurityData']);
Route::get('/media/{id}', [UserController::class, 'printImageForm']);
Route::post('/media/{id}', [UserController::class, 'setNewImage']);
Route::get('/delete/{id}', [UserController::class, 'delete']);
Route::get('/profile/{id}', [UserController::class, 'printUserProfile']);
