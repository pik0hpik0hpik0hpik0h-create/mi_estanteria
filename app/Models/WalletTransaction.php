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

    protected $casts = [
        'monto' => 'decimal:2',
    ];


    public function wallet()
    {
        return $this->belongsTo(WriterWallet::class, 'wallet_id');
    }
}
