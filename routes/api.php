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
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout'])->name('api.logout');
Route::post('/v1/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/v1/register', [AuthController::class, 'register'])->name('api.register');

//Rutas Categories
Route::middleware('auth:api')->get('/v1/categories', [CategoryController::class, 'index'])->name('api.categories.index');
Route::middleware('auth:api')->post('/v1/categories', [CategoryController::class, 'store'])->name('api.categories.store');
Route::middleware('auth:api')->get('/v1/categories/{id}', [CategoryController::class, 'show'])->name('api.categories.show');
