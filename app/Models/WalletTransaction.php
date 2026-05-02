<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'tipo',
        'monto',
        'descripcion',
        'referencia_id'
    ];

    public function wallet()
    {
        return $this->belongsTo(WriterWallet::class, 'wallet_id');
    }
}
