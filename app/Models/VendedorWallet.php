<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendedorWallet extends Model
{
    protected $table = 'vendedor_wallets';

    protected $fillable = [
        'vendedor_id',
        'saldo_disponible',
        'saldo_retenido',
        'total_generado',
        'total_pagado'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
}