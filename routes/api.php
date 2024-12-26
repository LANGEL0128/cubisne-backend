<?php

use Illuminate\Http\Request;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::get('logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware(['auth:api']);
    Route::get('refresh', [App\Http\Controllers\Api\AuthController::class, 'refresh'])->middleware(['auth:api']);
    Route::get('me', [App\Http\Controllers\Api\AuthController::class, 'me'])->middleware(['auth:api']);
    Route::post('profile', [App\Http\Controllers\Api\AuthController::class, 'profile'])->middleware(['auth:api']);
    Route::post('password/email', [App\Http\Controllers\Api\AuthController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [App\Http\Controllers\Api\AuthController::class, 'reset'])->name('password.reset');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {
    Route::get('/', [App\Http\Controllers\Api\UserController::class, 'index']);
    Route::get('/simple', [App\Http\Controllers\Api\UserController::class, 'simple']);
    Route::post('/', [App\Http\Controllers\Api\UserController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\Api\UserController::class, 'show']);
    Route::post('/update/{id}', [App\Http\Controllers\Api\UserController::class, 'update'])->middleware(['auth:api']);
    Route::delete('/{id}', [App\Http\Controllers\Api\UserController::class, 'destroy'])->middleware(['auth:api']);
    Route::get('/change/{id}', [App\Http\Controllers\Api\UserController::class, 'change'])->middleware(['auth:api']);
});
