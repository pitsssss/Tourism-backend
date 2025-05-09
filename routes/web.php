<?php

use Illuminate\Support\Facades\Route;

Auth::routes([
    'verify' => true
]);
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
