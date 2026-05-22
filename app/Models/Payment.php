<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'proveedor',
        'transaction_id',
        'paypal_order_id',
        'paypal_capture_id',
        'monto',
        'moneda',
        'estado',
        'response_json',
        'pagado_en'
    ];

    protected $casts = [
        'response_json' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}