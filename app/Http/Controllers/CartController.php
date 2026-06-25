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

    public function add(Request $request, Book $book, $codigo_vendedor = null)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDACIONES BÁSICAS
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
        | LÓGICA DE AFILIADOS (VENDEDORES)
        |--------------------------------------------------------------------------
        */
        
        $vendedor_id = null;
        $descuento_aplicado = 0;

        if ($codigo_vendedor) {
            // Buscamos si el código de referido existe y está activo
            $vendedor = \App\Models\Vendedor::where('codigo_vendedor', $codigo_vendedor)
                ->where('estado', 'aprobado')
                ->first();

            // Verificamos que no sea el mismo usuario intentando auto-referirse
            if ($vendedor && $vendedor->user_id !== Auth::id()) {
                $vendedor_id = $vendedor->id;

                // Obtenemos el descuento configurado dinámicamente en BD (por defecto 10%)
                $porcentaje_descuento = \App\Models\Configuracion::where('clave', 'descuento_comprador')->value('valor') ?? 10;
                
                // Calculamos cuánto dinero se le descontará
                $descuento_aplicado = ($book->precio * $porcentaje_descuento) / 100;
            }
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
        | CREAR ITEM (CON DESCUENTO)
        |--------------------------------------------------------------------------
        */

        $subtotal_final = $book->precio - $descuento_aplicado;
        // Aseguramos que el subtotal nunca sea negativo
        if($subtotal_final < 0) {
             $subtotal_final = 0;
        }

        CartItem::create([
            'cart_id'            => $cart->id,
            'book_id'            => $book->id,
            'vendedor_id'        => $vendedor_id,        // Registramos el referido
            'cantidad'           => 1,
            'precio_unitario'    => $book->precio,
            'descuento_aplicado' => $descuento_aplicado, // Registramos la rebaja
            'subtotal'           => $subtotal_final      // Guardamos el precio rebajado
        ]);

        $mensaje = 'Libro agregado al carrito.';
        if ($descuento_aplicado > 0) {
            $mensaje = '¡Libro agregado! Descuento de afiliado aplicado.';
        }

        return redirect()
            ->route('cart.index')
            ->with('success', $mensaje);
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