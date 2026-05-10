<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
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
}
