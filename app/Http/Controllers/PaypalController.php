<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserBook;

class PaypalController extends Controller
{
    public function create(Request $request)
    {
        try {

            $bookId = $request->book_id;

            session([
                'paypal_book_id' => $bookId
            ]);

            $book =
                Book::findOrFail(
                    $bookId
                );

            $provider =
                new PayPalClient;

            $provider
                ->setApiCredentials(
                    config('paypal')
                );

            $token =
                $provider
                ->getAccessToken();

            $provider
                ->setAccessToken(
                    $token
                );

            $response =
                $provider
                ->createOrder([

                    "intent" =>
                        "CAPTURE",

                    "purchase_units" => [

                        [

                            "amount" => [

                                "currency_code" =>
                                    "USD",

                                "value" =>
                                    number_format(
                                        $book->precio,
                                        2,
                                        '.',
                                        ''
                                    )

                            ]

                        ]

                    ],

                    "application_context" => [

                        "return_url" =>
                            route(
                                'paypal.success'
                            ),

                        "cancel_url" =>
                            route(
                                'paypal.cancel'
                            )

                    ]

                ]);

            if (
                isset(
                    $response['id']
                )
            ) {

                foreach (
                    $response['links']
                    as $link
                ) {

                    if (
                        $link['rel']
                        === 'approve'
                    ) {

                        return redirect(
                            $link['href']
                        );
                    }
                }
            }

            return back()
                ->with(
                    'error',
                    'No se pudo iniciar el pago'
                );

        } catch (\Exception $e) {

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function success(
        Request $request
    )
    {
        try {

            $provider =
                new PayPalClient;

            $provider
                ->setApiCredentials(
                    config('paypal')
                );

            $token =
                $provider
                ->getAccessToken();

            $provider
                ->setAccessToken(
                    $token
                );

            $response =
                $provider
                ->capturePaymentOrder(
                    $request->token
                );

            if (

                isset(
                    $response['status']
                )

                &&

                $response['status']
                === 'COMPLETED'

            ) {

                $bookId =
                    session(
                        'paypal_book_id'
                    );

                $book =
                    Book::findOrFail(
                        $bookId
                    );

                $order =
                    Order::create([

                        'user_id' =>
                            Auth::id(),

                        'codigo' =>
                            'ORD-'
                            .
                            now()
                            ->timestamp,

                        'subtotal' =>
                            $book->precio,

                        'impuestos' =>
                            0,

                        'comision' =>
                            0,

                        'total' =>
                            $book->precio,

                        'moneda' =>
                            'USD',

                        'estado' =>
                            'pagado',

                        'paypal_order_id' =>
                            $response['id'],

                        'paypal_capture_id' =>

                            $response
                            ['purchase_units']
                            [0]
                            ['payments']
                            ['captures']
                            [0]
                            ['id']

                            ??

                            null,

                        'pagado_en' =>
                            now(),

                    ]);

                $orderItem =
                    OrderItem::create([

                        'order_id' =>
                            $order->id,

                        'book_id' =>
                            $book->id,

                        'writer_id' =>
                            $book->writer_id,

                        'precio' =>
                            $book->precio,

                        'comision_plataforma' =>
                            0,

                        'ganancia_writer' =>
                            $book->precio,

                    ]);

                UserBook::firstOrCreate(

                    [

                        'user_id' =>
                            Auth::id(),

                        'book_id' =>
                            $book->id,

                    ],

                    [

                        'order_item_id' =>
                            $orderItem->id,

                        'acceso_desde' =>
                            now(),

                    ]

                );

                session()
                    ->forget(
                        'paypal_book_id'
                    );

                return redirect()
                    ->route(
                        'perfil'
                    )
                    ->with(
                        'success',
                        'Libro agregado a tu estantería'
                    );
            }

            return redirect('/')
                ->with(
                    'error',
                    'Pago no completado'
                );

        } catch (\Exception $e) {

            return redirect('/')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function cancel()
    {
        session()
            ->forget(
                'paypal_book_id'
            );

        return redirect('/')
            ->with(
                'error',
                'Pago cancelado'
            );
    }
}

