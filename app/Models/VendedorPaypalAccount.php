<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendedorPaypalAccount extends Model
{
    use HasFactory;

    protected $table = 'vendedor_paypal_accounts';

    protected $fillable = [
        'vendedor_id',
        'paypal_email',
        'paypal_nombre_cuenta',
        'paypal_merchant_id',
        'paypal_verificado',
        'verificado_en',
        'estado',
    ];

    protected $casts = [
        'paypal_verificado' => 'boolean',
        'verificado_en'     => 'datetime',
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
}
