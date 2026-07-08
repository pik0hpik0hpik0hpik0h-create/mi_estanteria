<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    // MOVIMIENTOS / TRANSACCIONES DE LA BILLETERA
    public function movimientos()
    {
        $user = Auth::user()->load('roles', 'perfil', 'writer.wallet', 'vendedor.wallet');

        $writerWalletId   = ($user->isWriter() && $user->writer) ? ($user->writer->wallet->id ?? null) : null;
        $vendedorWalletId = ($user->isVendedor() && $user->vendedor) ? ($user->vendedor->wallet->id ?? null) : null;

        // Las transacciones de vendedor se marcan con "(Vendedor)" en la descripción,
        // ya que wallet_id puede apuntar a writer_wallets o vendedor_wallets.
        $movimientos = WalletTransaction::where(function ($q) use ($writerWalletId, $vendedorWalletId) {

            if ($writerWalletId) {
                $q->orWhere(function ($qq) use ($writerWalletId) {
                    $qq->where('wallet_id', $writerWalletId)
                       ->where(function ($qqq) {
                            $qqq->whereNull('descripcion')
                                ->orWhere('descripcion', 'not like', '%(Vendedor)%');
                       });
                });
            }

            if ($vendedorWalletId) {
                $q->orWhere(function ($qq) use ($vendedorWalletId) {
                    $qq->where('wallet_id', $vendedorWalletId)
                       ->where('descripcion', 'like', '%(Vendedor)%');
                });
            }

            // Si el usuario no tiene billeteras, no devolver nada
            if (!$writerWalletId && !$vendedorWalletId) {
                $q->whereRaw('1 = 0');
            }

        })->latest()->paginate(15);

        // Saldo disponible combinado (mismo criterio que el perfil)
        $saldo_disponible = 0;
        if ($user->isWriter() && $user->writer) {
            $saldo_disponible += $user->writer->wallet->saldo_disponible ?? 0;
        }
        if ($user->isVendedor() && $user->vendedor) {
            $saldo_disponible += $user->vendedor->wallet->saldo_disponible ?? 0;
        }

        $wallet = (object) ['saldo_disponible' => $saldo_disponible];

        return view('auth.movimientos_wallet', compact('user', 'wallet', 'movimientos'));
    }
}
