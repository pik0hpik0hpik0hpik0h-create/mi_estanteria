<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WriterPaypalAccount extends Model
{
    use HasFactory;

    protected $table = 'writer_paypal_accounts';

    protected $fillable = [
        'writer_id',
        'paypal_email',
        'paypal_nombre_cuenta',
        'paypal_merchant_id',
        'paypal_verificado',
        'verificado_en',
        'estado',
    ];

    protected $casts = [
        'paypal_verificado' => 'boolean',
        'verificado_en' => 'datetime',
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
}