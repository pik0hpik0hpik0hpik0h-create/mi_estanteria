<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// RUTA INDEX
Route::get('/', function () {

    if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    return view('landing.index');

})->name('index');

// RUTAS GENERALES
Route::get('/login', [UsuarioController::class, 'form'])->name('login');
Route::post('/login', [UsuarioController::class, 'login'])->name('login.submit');
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

Route::get('/register', [UsuarioController::class, 'create'])->name('register.create');
Route::post('/register', [UsuarioController::class, 'store'])->name('register.store');

Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// RUTAS PARA USUARIO AUTENTICADOS PERO NO VERIFICADOS
Route::middleware(['auth','not.verified'])->group(function () {

    Route::post('/logout_not_verified', [UsuarioController::class, 'logout'])->name('logout_not_verified');

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/')->with('success', 'Correo verificado correctamente');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success','Correo reenviado');
    })->middleware('throttle:6,1')->name('verification.send');

});

// RUTAS PARA USUARIOS AUTENTICADOS Y VERIFICADOS
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('perfil');
    Route::post('/editar_perfil', [UsuarioController::class, 'editar'])->name('editar_perfil');

});





