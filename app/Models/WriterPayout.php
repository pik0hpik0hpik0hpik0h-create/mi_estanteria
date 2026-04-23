<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WriterPayout extends Model
{
    use HasFactory;

    protected $table = 'writer_payouts';

    protected $fillable = [
        'writer_id',
        'monto',
        'moneda',
        'paypal_email',
        'paypal_batch_id',
        'paypal_item_id',
        'transaction_id',
        'status',
        'error_message',
        'response_json',
        'paid_at',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'paid_at' => 'datetime',
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