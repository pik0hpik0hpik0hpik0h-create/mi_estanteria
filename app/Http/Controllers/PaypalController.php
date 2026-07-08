<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserBook;
use App\Models\WriterWallet;
use App\Models\WalletTransaction;
use App\Models\Configuracion;
use App\Models\VendedorWallet;

class PaypalController extends Controller
{
    /**
     * INICIAR PAGO DE TODO EL CARRITO
     */
    public function create(Request $request)
    {
        try {
            // 1. Buscamos el carrito activo del usuario logueado
            $cart = Cart::with('items.book')
                ->where('user_id', Auth::id())
                ->where('estado', 'activo')
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                return back()->with('error', 'Tu carrito está vacío.');
            }

            // 2. Calculamos los mismos montos exactos que mostramos en la vista del carrito
            $subtotal  = $cart->items->sum('subtotal');
            $comision  = $subtotal * 0.10;
            $impuestos = ($subtotal + $comision) * 0.15;
            $total     = round($subtotal + $comision + $impuestos, 2);

            // Guardamos el ID del carrito en sesión para recuperarlo al volver de PayPal
            session(['paypal_cart_id' => $cart->id]);

            // 3. Conectamos con PayPal
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "description" => "Compra en Mi Estantería (" . $cart->items->count() . " libros)",
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($total, 2, '.', '')
                        ]
                    ]
                ],
                "application_context" => [
                    "return_url" => route('paypal.success'),
                    "cancel_url" => route('paypal.cancel')
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return redirect($link['href']);
                    }
                }
            }

            return back()->with('error', 'No se pudo generar el enlace de pago con PayPal.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }

    /**
     * PAGO COMPLETADO EXITOSAMENTE
     */
    public function success(Request $request)
    {
        try {
            $cartId = session('paypal_cart_id');
            if (!$cartId) {
                return redirect()->route('index')->with('error', 'Sesión de pago expirada.');
            }

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            $response = $provider->capturePaymentOrder($request->token);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {

                // Recuperamos el carrito con todos sus datos
                $cart = Cart::with('items.book')->findOrFail($cartId);

                $subtotal        = $cart->items->sum('subtotal');
                $descuento_total = $cart->items->sum('descuento_aplicado');
                $comision        = $subtotal * 0.10;
                $impuestos       = ($subtotal + $comision) * 0.15;
                $total_pagado    = round($subtotal + $comision + $impuestos, 2);

                // Capturamos el ID de la transacción de PayPal
                $captureId = $response['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

                DB::transaction(function () use ($cart, $subtotal, $descuento_total, $impuestos, $comision, $total_pagado, $response, $captureId) {

                    // A. CREAR LA ORDEN PRINCIPAL
                    $order = Order::create([
                        'user_id'           => Auth::id(),
                        'codigo'            => 'ORD-' . strtoupper(uniqid()),
                        'subtotal'          => $subtotal,
                        'descuento_total'   => $descuento_total,
                        'impuestos'         => $impuestos,
                        'comision'          => $comision,
                        'total'             => $total_pagado,
                        'moneda'            => 'USD',
                        'estado'            => 'pagado',
                        'paypal_order_id'   => $response['id'],
                        'paypal_capture_id' => $captureId,
                        'pagado_en'         => now(),
                    ]);

                    // Obtenemos el % dinámico de comisión para vendedores (o 15% por defecto)
                    $porcentaje_vendedor = Configuracion::where('clave', 'comision_vendedor')->value('valor') ?? 15;

                    // B. RECORRER CADA LIBRO DEL CARRITO Y REPARTIR EL DINERO
                    foreach ($cart->items as $item) {
                        $book = $item->book;

                        // 1. Calcular comisión de afiliado (si alguien lo recomendó)
                        $comision_vendedor_item = 0;
                        if ($item->vendedor_id) {
                            $comision_vendedor_item = round(($item->subtotal * $porcentaje_vendedor) / 100, 2);
                        }

                        // 2. Comisión de la plataforma sobre el libro (Ej: 10%)
                        $comision_plataforma_item = round($item->subtotal * 0.10, 2);

                        // 3. Lo que sobra va limpio al escritor
                        $ganancia_writer = $item->subtotal - $comision_vendedor_item - $comision_plataforma_item;
                        if ($ganancia_writer < 0) $ganancia_writer = 0;

                        // C. GUARDAR EL ÍTEM DE LA ORDEN
                        $orderItem = OrderItem::create([
                            'order_id'            => $order->id,
                            'book_id'             => $book->id,
                            'writer_id'           => $book->writer_id,
                            'vendedor_id'         => $item->vendedor_id,
                            'precio'              => $item->precio_unitario,
                            'descuento_aplicado'  => $item->descuento_aplicado,
                            'comision_plataforma' => $comision_plataforma_item,
                            'comision_vendedor'   => $comision_vendedor_item,
                            'ganancia_writer'     => $ganancia_writer,
                        ]);

                        // D. DAR ACCESO DE LECTURA AL COMPRADOR
                        UserBook::firstOrCreate(
                            ['user_id' => Auth::id(), 'book_id' => $book->id],
                            ['order_item_id' => $orderItem->id, 'acceso_desde' => now()]
                        );

                        // E. DEPOSITAR DINERO AL ESCRITOR EN SU BILLETERA
                        if ($book->writer_id && $ganancia_writer > 0) {
                            $wallet = WriterWallet::firstOrCreate(
                                ['writer_id' => $book->writer_id],
                                ['saldo_disponible' => 0, 'saldo_retenido' => 0, 'total_generado' => 0, 'total_pagado' => 0]
                            );

                            $wallet->saldo_disponible += $ganancia_writer;
                            $wallet->total_generado   += $ganancia_writer;
                            $wallet->save();

                            WalletTransaction::create([
                                'wallet_id'     => $wallet->id,
                                'tipo'          => 'ingreso',
                                'monto'         => $ganancia_writer,
                                'descripcion'   => "Venta de libro: {$book->titulo} (Ref: {$order->codigo})",
                                'referencia_id' => $orderItem->id
                            ]);
                        }
                        // F. NUEVO: DEPOSITAR GANANCIA DIGITAL AL VENDEDOR
                        if ($item->vendedor_id && $comision_vendedor_item > 0) {
                            $vWallet = VendedorWallet::firstOrCreate(
                                ['vendedor_id' => $item->vendedor_id],
                                ['saldo_disponible' => 0, 'saldo_retenido' => 0, 'total_generado' => 0, 'total_pagado' => 0]
                            );

                            $vWallet->saldo_disponible += $comision_vendedor_item;
                            $vWallet->total_generado   += $comision_vendedor_item;
                            $vWallet->save();

                            WalletTransaction::create([
                                'wallet_id'     => $vWallet->id,
                                'tipo'          => 'ingreso',
                                'monto'         => $comision_vendedor_item,
                                'descripcion'   => "Comisión por venta: {$book->titulo} (Ref: {$order->codigo}) (Vendedor)",
                                'referencia_id' => $orderItem->id
                            ]);
                        }
                    }

                    // G. VACIAR Y CERRAR EL CARRITO
                    $cart->items()->delete();
                    $cart->update(['estado' => 'convertido']);
                });

                session()->forget('paypal_cart_id');

                return redirect()->route('perfil')->with('success', '¡Compra completada con éxito! Tus libros ya están en tu estantería.');
            }

            return redirect()->route('cart.index')->with('error', 'El pago no fue completado por PayPal.');

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Ocurrió un error en el pago: ' . $e->getMessage());
        }
    }

    /**
     * PAGO CANCELADO POR EL USUARIO
     */
    public function cancel()
    {
        session()->forget('paypal_cart_id');
        return redirect()->route('cart.index')->with('warning', 'Has cancelado el proceso de pago.');
    }
}