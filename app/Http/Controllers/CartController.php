<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MOSTRAR CARRITO
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $cart = Cart::with([
                'items.book.category',
                'items.book.writer'
            ])
            ->where('user_id', Auth::id())
            ->where('estado', 'activo')
            ->first();

        $subtotal = 0;
        $comision = 0;
        $impuestos = 0;
        $total = 0;

        if ($cart) {

            $subtotal = $cart->items->sum('subtotal');

            /*
            |--------------------------------------------------------------------------
            | CONFIGURACIÓN ECONÓMICA
            |--------------------------------------------------------------------------
            */

            // COMISIÓN PLATAFORMA
            $comision = $subtotal * 0.10;

            // IMPUESTOS
            $impuestos = ($subtotal + $comision) * 0.15;

            // TOTAL
            $total = $subtotal + $comision + $impuestos;
        }

        return view('cart.cart', compact(
            'cart',
            'subtotal',
            'comision',
            'impuestos',
            'total'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | AGREGAR LIBRO AL CARRITO
    |--------------------------------------------------------------------------
    */

    public function add(Book $book)
    {
    /*
    |--------------------------------------------------------------------------
    | VALIDACIONES
    |--------------------------------------------------------------------------
    */

    // NO AGREGAR SU PROPIO LIBRO
    if (
        $book->writer &&
        $book->writer->user_id === Auth::id()
    ) {
        return back()->with(
            'error',
            'No puedes comprar tu propio libro.'
        );
    }

    // LIBRO PUBLICADO
    if ($book->estado !== 'publicado') {

        return back()->with(
            'error',
            'Este libro no está disponible.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | OBTENER O CREAR CARRITO
    |--------------------------------------------------------------------------
    */

    $cart = Cart::firstOrCreate(
        [
            'user_id' => Auth::id(),
            'estado' => 'activo'
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SI YA EXISTE
    |--------------------------------------------------------------------------
    */

    $item = CartItem::where('cart_id', $cart->id)
        ->where('book_id', $book->id)
        ->first();

    /*
    |--------------------------------------------------------------------------
    | EVITAR DUPLICADOS
    |--------------------------------------------------------------------------
    */

    if ($item) {

        return back()->with(
            'warning',
            'Este libro ya está en tu carrito.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR ITEM
    |--------------------------------------------------------------------------
    */

    CartItem::create([
        'cart_id' => $cart->id,
        'book_id' => $book->id,
        'cantidad' => 1,
        'precio_unitario' => $book->precio,
        'subtotal' => $book->precio
    ]);

    return redirect()
        ->route('cart.index')
        ->with(
            'success',
            'Libro agregado al carrito.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINAR ITEM
    |--------------------------------------------------------------------------
    */

    public function remove(CartItem $cartItem)
    {
        /*
        |--------------------------------------------------------------------------
        | SEGURIDAD
        |--------------------------------------------------------------------------
        */

        if (
            $cartItem->cart->user_id !== Auth::id()
        ) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with(
            'success',
            'Libro eliminado del carrito.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LIMPIAR CARRITO
    |--------------------------------------------------------------------------
    */

    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('estado', 'activo')
            ->first();

        if ($cart) {

            $cart->items()->delete();
        }

        return back()->with(
            'success',
            'Carrito vaciado correctamente.'
        );
    }
}