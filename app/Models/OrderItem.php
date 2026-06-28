<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'book_id',
        'writer_id',
        'vendedor_id',
        'precio',
        'descuento_aplicado',
        'comision_plataforma',
        'comision_vendedor',
        'ganancia_writer'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function writer()
    {
        return $this->belongsTo(Writer::class);
    }
}