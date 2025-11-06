<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\HelloController;

Route::get('/hello', [HelloController::class, 'index']);

Route::get('/hello/{name}', [HelloController::class, 'greet']);

Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

