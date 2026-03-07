<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {return view('landing.index');})->name('index');

Route::get('/login', [UsuarioController::class, 'form'])->name('login');
Route::post('/login', [UsuarioController::class, 'login'])->name('login.submit');
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

Route::get('/register', [UsuarioController::class, 'create'])->name('register.create');
Route::post('/register', [UsuarioController::class, 'store'])->name('register.store');

Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/')->with('success', 'Correo verificado correctamente');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('success','Correo reenviado');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');