<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/redirect-handler', [RedirectController::class, 'handle']);
