<?php

namespace App\Http\Controllers;

use App\Models\WithdrawRequest;
use Illuminate\Http\Request;

class AdminWithdrawalController extends Controller
{

    /**
     * Verificar que el usuario sea administrador
     */
    private function checkAdmin()
    {
        if (
            !auth()->user()
            ->roles()
            ->where('rol', 'admin')
            ->exists()
        ) {
            abort(403, 'Acceso denegado.');
        }
    }



    /**
     * Lista de solicitudes pendientes
     */
    public function index()
    {

        $this->checkAdmin();


        $withdrawals = WithdrawRequest::with([
            'vendedor.user',
            'writer.user',
            'writerWallet',
            'vendedorWallet'
        ])
        ->where('estado', 'pendiente')
        ->latest()
        ->paginate(15);



        return view(
            'admin.withdrawals.index',
            compact('withdrawals')
        );

    }




    /**
     * Ver detalle del retiro
     */
    public function show(WithdrawRequest $withdrawal)
    {

        $this->checkAdmin();


        $withdrawal->load([
            'vendedor.user',
            'writer.user',
            'writerWallet',
            'vendedorWallet'
        ]);


        return view(
            'admin.withdrawals.show',
            compact('withdrawal')
        );

    }




    /**
     * Aprobar retiro
     */
    public function approve(WithdrawRequest $withdrawal)
    {

        $this->checkAdmin();



        if ($withdrawal->estado !== 'pendiente') {

            return back()
                ->with(
                    'error',
                    'Este retiro ya fue procesado.'
                );

        }



        /*
        Aquí después puedes agregar:
        - descontar saldo retenido
        - enviar pago PayPal
        - registrar transacción wallet
        */


        $withdrawal->update([

            'estado' => 'aprobado'

        ]);



        return redirect()
            ->route('admin.retiros.index')
            ->with(
                'success',
                'Retiro aprobado correctamente.'
            );

    }





    /**
     * Rechazar retiro
     */
    public function reject(
    Request $request,
    WithdrawRequest $withdrawal
)
{

    $this->checkAdmin();


    if ($withdrawal->estado !== 'pendiente') {

        return back()
            ->with(
                'error',
                'Este retiro ya fue procesado.'
            );

    }



    // Detectar wallet correcta
    if ($withdrawal->writer_id) {

        $wallet = $withdrawal->writerWallet;

    } elseif ($withdrawal->vendedor_id) {

        $wallet = $withdrawal->vendedorWallet;

    } else {

        return back()
            ->with(
                'error',
                'No se encontró la wallet asociada.'
            );

    }



    if (!$wallet) {

        return back()
            ->with(
                'error',
                'La wallet no existe.'
            );

    }



    // DEVOLVER DINERO
    $wallet->update([

        'saldo_disponible' => 
            $wallet->saldo_disponible + $withdrawal->monto,


        'saldo_retenido' => 
            $wallet->saldo_retenido - $withdrawal->monto

    ]);




    // Cambiar estado del retiro
    $withdrawal->update([

        'estado' => 'rechazado'

    ]);




    return redirect()
        ->route('admin.retiros.index')
        ->with(
            'success',
            'Retiro rechazado y saldo devuelto correctamente.'
        );

}



}