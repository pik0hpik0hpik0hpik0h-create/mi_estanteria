<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    protected $fillable = [
        'writer_id',
        'vendedor_id',
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
        // Apuntamos a Writer::class porque en la migración la llave foránea va a la tabla writers
        return $this->belongsTo(Writer::class, 'writer_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id');
    }

    // Dividimos la relación de la billetera según el rol
    public function writerWallet()
    {
        return $this->belongsTo(WriterWallet::class, 'wallet_id');
    }

    public function vendedorWallet()
    {
        return $this->belongsTo(VendedorWallet::class, 'wallet_id');
    }
}