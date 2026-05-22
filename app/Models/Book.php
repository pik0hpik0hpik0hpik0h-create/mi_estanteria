<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model

{

     protected $fillable = [

        'writer_id',
        'book_category_id',

        'titulo',
        'slug',

        'descripcion_corta',
        'descripcion',

        'portada',

        'tipo',
        'formato',

        'idioma',
        'isbn',

        'paginas',
        'fecha_publicacion',

        'precio',
        'stock',

        'estado',

        'visibilidad',
        'destacado',

        'total_ventas',
        'promedio_rating',

        'meta_title',
        'meta_description',
    ];

    public function writer()
    {
        return $this->belongsTo(Writer::class);
    }

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    public function files()
    {
        return $this->hasMany(BookFile::class);
    }

    public function images()
    {
        return $this->hasMany(BookImage::class);
    }

    public function cartItems()
{
    return $this->hasMany(CartItem::class);
}

public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}

public function buyers()
{
    return $this->hasMany(UserBook::class);
}
}
