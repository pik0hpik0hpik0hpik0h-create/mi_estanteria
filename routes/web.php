<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('landing.index');
})->name('index');

Route::get('/login', [UsuarioController::class, 'form'])->name('login');
Route::post('/login', [UsuarioController::class, 'login'])->name('login.submit');
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

Route::get('/register', [UsuarioController::class, 'create'])->name('register.create');
Route::post('/register', [UsuarioController::class, 'store'])->name('register.store');