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
Route::get('/users', [UserController::class, 'getUsersList']);
