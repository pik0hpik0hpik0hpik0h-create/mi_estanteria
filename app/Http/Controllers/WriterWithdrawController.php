<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WithdrawRequest;
use App\Models\WalletTransaction;

class WriterWithdrawController extends Controller
{
    public function store(Request $request)
    {
         

        $user = auth()->user();

        // 1. validar input
        $request->validate([
            'monto' => 'required|numeric|min:15'
        ]);

        try {
            DB::transaction(function () use ($user, $request) {
 
                $writer = $user->writer;

                $wallet = $writer->wallet ?? null;
                $payAccount = $writer->payAccount ?? null;

                // 2. validar PayPal
                if (!$payAccount || !$payAccount->paypal_verificado) {
                    throw new \Exception('Debes tener una cuenta PayPal verificada');
                }

                // 3. validar solicitud pendiente
                $tienePendiente = $writer->withdrawRequests()
                    ->where('estado', 'pendiente')
                    ->exists();

                if ($tienePendiente) {
                    throw new \Exception('Ya tienes una solicitud pendiente');
                }

                // 4. validar saldo
                if ($wallet->saldo_disponible < $request->monto) {
                    throw new \Exception('Saldo insuficiente');
                }

                // 5. mover dinero (CLAVE 🔥)
                $wallet->saldo_disponible -= $request->monto;
                $wallet->saldo_retenido += $request->monto;
                $wallet->save();

                // 6. crear solicitud
                $withdraw = WithdrawRequest::create([
                    'writer_id' => $writer->id,
                    'wallet_id' => $wallet->id,
                    'monto' => $request->monto,
                    'estado' => 'pendiente',
                    'paypal_email' => $payAccount->paypal_email,
                    'paypal_merchant_id' => $payAccount->paypal_merchant_id,
                ]);

                // 7. registrar movimiento
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'tipo' => 'retiro',
                    'monto' => $request->monto,
                    'descripcion' => 'Solicitud de retiro',
                    'referencia_id' => $withdraw->id,
                ]);
            });

            return back()->with('success', 'Solicitud enviada correctamente');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}