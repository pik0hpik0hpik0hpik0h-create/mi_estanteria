<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Writer extends Model
{
    use HasFactory;

    protected $table = 'writers';

    protected $fillable = [
        'user_id',
        'nombre_pluma',
        'documento_identidad',
        'tipo_documento',
        'estado',
        'aprobado_en',
    ];

    protected $casts = [
        'aprobado_en' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function perfil()
    {
        return $this->hasOne(Perfil::class, 'user_id', 'user_id');
    }

    public function paypalAccount()
    {
        return $this->hasOne(WriterPaypalAccount::class);
    }

    public function wallet()
    {
        return $this->hasOne(WriterWallet::class);
    }
}