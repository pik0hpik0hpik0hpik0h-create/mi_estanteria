<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookFile extends Model
{
    protected $fillable = [

        'book_id',

        'tipo',

        'archivo',

        'nombre_original',

        'peso',

        'mime_type',

        'extension',

        'version',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
