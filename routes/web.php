<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('auth')->group(function () {
    Route::get('/users', [ChatController::class, 'index'])->name('users');
    Route::get('/chat/{id}', [ChatController::class, 'chat'])->name('chat');
    Route::post('/message/{id}', [ChatController::class, 'store'])->name('message.store');
});