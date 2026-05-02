<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WriterWallet extends Model
{
    use HasFactory;

    protected $table = 'writer_wallets';

    protected $fillable = [
        'writer_id',
        'saldo_disponible',
        'saldo_retenido',
        'total_generado',
        'total_pagado',
    ];

    protected $casts = [
        'saldo_disponible' => 'decimal:2',
        'saldo_retenido' => 'decimal:2',
        'total_generado' => 'decimal:2',
        'total_pagado' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function writer()
    {
        return $this->belongsTo(Writer::class);
    }

    public function withdrawRequests()
    {
        return $this->hasMany(WithdrawRequest::class, 'wallet_id');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }
}