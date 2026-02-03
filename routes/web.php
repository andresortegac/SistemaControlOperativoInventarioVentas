<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/
Route::controller(LoginController::class)->group(function () {

    Route::get('/', 'show')
        ->name('login');

    Route::post('/login', 'login')
        ->name('login.post');

    Route::post('/logout', 'logout')
        ->name('logout');
});