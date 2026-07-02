<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\WithdrawRequest;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WriterWithdrawController extends Controller
{

    // HISTORIAL DE SOLICITUDES DE RETIRO
    public function historial_solicitudes_retiro()
    {
        $user = Auth::user()->load('roles', 'perfil', 'writer.wallet', 'vendedor.wallet');

        $writerId = $user->writer->id ?? null;
        $vendedorId = $user->vendedor->id ?? null;

        // Buscamos los retiros que pertenezcan al escritor O al vendedor
        $query = WithdrawRequest::where(function($q) use ($writerId, $vendedorId) {
            if ($writerId) $q->orWhere('writer_id', $writerId);
            if ($vendedorId) $q->orWhere('vendedor_id', $vendedorId);
        });

        $lastWithdraw = (clone $query)->latest()->first();
        $hasPending = (clone $query)->where('estado', 'pendiente')->exists();
        $payouts = (clone $query)->latest()->paginate(10);

        // Calculamos el saldo disponible combinado
        $saldo_disponible = 0;
        if ($user->isWriter() && $user->writer) {
            $saldo_disponible += $user->writer->wallet->saldo_disponible ?? 0;
        }
        if ($user->isVendedor() && $user->vendedor) {
            $saldo_disponible += $user->vendedor->wallet->saldo_disponible ?? 0;
        }

        $wallet = (object) ['saldo_disponible' => $saldo_disponible];

        return view('auth.historial_retiros', compact(
            'user',
            'wallet',
            'lastWithdraw',
            'hasPending',
            'payouts'
        ));
    }

    // CREAR SOLICITUD DE RETIRO
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'monto' => 'required|numeric|min:15'
        ]);

        try {
            DB::transaction(function () use ($user, $request) {
                $montoRestante = $request->monto;

                $writer = $user->writer;
                $vendedor = $user->vendedor;

                $writerWallet = ($writer && $user->isWriter()) ? $writer->wallet : null;
                $vendedorWallet = ($vendedor && $user->isVendedor()) ? $vendedor->wallet : null;

                $totalDisponible = ($writerWallet->saldo_disponible ?? 0) + ($vendedorWallet->saldo_disponible ?? 0);

                // 1. Validar saldo total
                if ($montoRestante > $totalDisponible) {
                    throw new \Exception('Saldo insuficiente. Tienes $' . number_format($totalDisponible, 2) . ' disponibles.');
                }

                // 2. Validar si ya hay solicitudes pendientes
                $tienePendienteWriter = $writer ? $writer->withdrawRequests()->where('estado', 'pendiente')->exists() : false;
                $tienePendienteVendedor = $vendedor ? $vendedor->withdrawRequests()->where('estado', 'pendiente')->exists() : false;

                if ($tienePendienteWriter || $tienePendienteVendedor) {
                    throw new \Exception('Ya tienes una solicitud pendiente. Espera a que sea procesada.');
                }

                // 3. Descontar del saldo de Vendedor (si tiene fondos)
                if ($vendedorWallet && $vendedorWallet->saldo_disponible > 0 && $montoRestante > 0) {
                    $montoADescontar = min($montoRestante, $vendedorWallet->saldo_disponible);
                    
                    $payAccount = $vendedor->payAccount;
                    if (!$payAccount || !$payAccount->paypal_verificado) {
                        throw new \Exception('Debes tener tu cuenta de PayPal verificada en tu perfil de Vendedor.');
                    }

                    $vendedorWallet->saldo_disponible -= $montoADescontar;
                    $vendedorWallet->saldo_retenido += $montoADescontar;
                    $vendedorWallet->save();

                    $withdraw = WithdrawRequest::create([
                        'vendedor_id' => $vendedor->id,
                        'wallet_id' => $vendedorWallet->id,
                        'monto' => $montoADescontar,
                        'estado' => 'pendiente',
                        'paypal_email' => $payAccount->paypal_email,
                        'paypal_merchant_id' => $payAccount->paypal_merchant_id,
                    ]);

                    WalletTransaction::create([
                        'wallet_id' => $vendedorWallet->id,
                        'tipo' => 'retiro',
                        'monto' => $montoADescontar,
                        'descripcion' => 'Solicitud de retiro (Vendedor)',
                        'referencia_id' => $withdraw->id,
                    ]);

                    $montoRestante -= $montoADescontar;
                }

                // 4. Si aún falta monto por cubrir (o si solo era escritor), descontar del saldo de Escritor
                if ($writerWallet && $writerWallet->saldo_disponible > 0 && $montoRestante > 0) {
                    $montoADescontar = min($montoRestante, $writerWallet->saldo_disponible);
                    
                    $payAccount = $writer->payAccount;
                    if (!$payAccount || !$payAccount->paypal_verificado) {
                        throw new \Exception('Debes tener tu cuenta de PayPal verificada en tu perfil de Escritor.');
                    }

                    $writerWallet->saldo_disponible -= $montoADescontar;
                    $writerWallet->saldo_retenido += $montoADescontar;
                    $writerWallet->save();

                    $withdraw = WithdrawRequest::create([
                        'writer_id' => $writer->id,
                        'wallet_id' => $writerWallet->id,
                        'monto' => $montoADescontar,
                        'estado' => 'pendiente',
                        'paypal_email' => $payAccount->paypal_email,
                        'paypal_merchant_id' => $payAccount->paypal_merchant_id,
                    ]);

                    WalletTransaction::create([
                        'wallet_id' => $writerWallet->id,
                        'tipo' => 'retiro',
                        'monto' => $montoADescontar,
                        'descripcion' => 'Solicitud de retiro (Escritor)',
                        'referencia_id' => $withdraw->id,
                    ]);

                    $montoRestante -= $montoADescontar;
                }

            });

            return back()->with('success', 'Solicitud enviada correctamente');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}