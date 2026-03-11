<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rol extends Model
{
    protected $table = 'roles';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'rol',
        'estado',
        'fecha_asignacion'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}