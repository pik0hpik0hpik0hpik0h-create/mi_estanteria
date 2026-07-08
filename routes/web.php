<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\WriterController;
use App\Http\Controllers\WriterWithdrawController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\AdminBookController;
use App\Http\Controllers\AdminWriterController;
use App\Http\Controllers\AdminWithdrawalController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\AdminVendedorController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\LibraryController;
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

// Lista de paises cacheada (la usa el select de pais del registro y del perfil)
Route::get('/paises', [PaisController::class, 'index'])->name('paises');

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

    Route::get('/perfil/escritor', [WriterController::class, 'create'])->name('writers_create');
    Route::post('/perfil/escritor', [WriterController::class, 'store'])->name('writers_store');

    Route::get('/perfil/vendedor', [VendedorController::class, 'create'])->name('vendedores_create');
    Route::post('/perfil/vendedor', [VendedorController::class, 'store'])->name('vendedores_store');

    Route::post('/writer/withdraw', [WriterWithdrawController::class, 'store'])->name('writer.withdraw.store');
    Route::get('/writer/withdraw/history', [WriterWithdrawController::class, 'historial_solicitudes_retiro'])->name('writer.withdraw_history');

    Route::get('/writer/libros/subir', [BookController::class, 'create'])->name('books.create');
    Route::post('/writer/libros/subir', [BookController::class, 'store'])->name('books.store');

    Route::get('/libros/{book}', [BookController::class, 'show'])->name('books.show');
    Route::put('/libros/{book}', [BookController::class, 'update'])->name('books.update');

    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrito/agregar/{book}/{codigo_vendedor?}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/carrito/eliminar/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/carrito/limpiar', [CartController::class, 'clear'])->name('cart.clear');

    Route::post('/checkout/paypal', [PaypalController::class, 'checkout'])->name('paypal.checkout');
    Route::get('/checkout/paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
    Route::get('/checkout/paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');

    Route::post('/paypal/create', [PaypalController::class, 'create'])->name('paypal.create');
    Route::get('/paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');

    Route::middleware('auth')->get('/mi-estanteria', [LibraryController::class, 'index'])->name('library.index');
    Route::get('/mi-estanteria/{userBook}', [LibraryController::class, 'leer'])->middleware('auth')->name('library.leer');

    // --- RUTAS DEL PANEL ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/libros-pendientes', [AdminBookController::class, 'index'])->name('books.index');
        Route::get('/libros-pendientes/{book}', [AdminBookController::class, 'show'])->name('books.show');
        Route::post('/libros-pendientes/{book}/autorizar', [AdminBookController::class, 'approve'])->name('books.approve');
        Route::post('/libros-pendientes/{book}/rechazar', [AdminBookController::class, 'reject'])->name('books.reject');

        // Autorización de escritores (verificación manual)
        Route::get('/escritores-pendientes', [AdminWriterController::class, 'index'])->name('writers.index');
        Route::get('/escritores-pendientes/{writer}', [AdminWriterController::class, 'show'])->name('writers.show');
        Route::post('/escritores-pendientes/{writer}/autorizar', [AdminWriterController::class, 'approve'])->name('writers.approve');
        Route::post('/escritores-pendientes/{writer}/rechazar', [AdminWriterController::class, 'reject'])->name('writers.reject');

        // Autorización de vendedores
        Route::get('/vendedores-pendientes', [AdminVendedorController::class, 'index'])->name('vendedores.index');
        Route::get('/vendedores-pendientes/{vendedor}', [AdminVendedorController::class, 'show'])->name('vendedores.show');
        Route::post('/vendedores-pendientes/{vendedor}/autorizar', [AdminVendedorController::class, 'approve'])->name('vendedores.approve');
        Route::post('/vendedores-pendientes/{vendedor}/rechazar', [AdminVendedorController::class, 'reject'])->name('vendedores.reject');

        // Autorización de retiros
        Route::get(
            '/retiros-pendientes',
            [AdminWithdrawalController::class, 'index']
        )->name('retiros.index');


        Route::get(
            '/retiros-pendientes/{withdrawal}',
            [AdminWithdrawalController::class, 'show']
        )->name('retiros.show');


        Route::post(
            '/retiros-pendientes/{withdrawal}/autorizar',
            [AdminWithdrawalController::class, 'approve']
        )->name('retiros.approve');


        Route::post(
            '/retiros-pendientes/{withdrawal}/rechazar',
            [AdminWithdrawalController::class, 'reject']
        )->name('retiros.reject');
            });

});