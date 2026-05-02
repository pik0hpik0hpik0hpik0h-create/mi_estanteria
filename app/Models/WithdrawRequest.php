<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    protected $fillable = [
        'writer_id',
        'wallet_id',
        'monto',
        'estado',
        'paypal_email',
        'paypal_merchant_id',
        'nota_admin',
        'procesado_por',
        'procesado_en'
    ];

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id');
    }

    public function wallet()
    {
        return $this->belongsTo(WriterWallet::class, 'wallet_id');
    }
}