<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Categories\CategoryController;
use Illuminate\Support\Facades\Route;

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

//Rutas Auth
Route::middleware('auth:api')->get('/me', [AuthController::class, 'me'])->name('api.me');
Route::middleware('auth:api')->post('/logout',[AuthController::class, 'logout'])->name('api.logout');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

//Rutas Categories
Route::middleware('auth:api')->get('/categories',[CategoryController::class, 'index'])->name('api.categories.index');
