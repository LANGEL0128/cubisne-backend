<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('/clear', function() {
    // Storage::deleteDirectory('public');
    // Storage::makeDirectory('public');
    Artisan::call('route:clear');
    Artisan::call('storage:link', []);
});

Route::get('/', function() {
    return redirect('api/documentation');
});

Route::get('not-authorized', function() {
    return response('', 401);
});
