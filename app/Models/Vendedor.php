<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedores';

    protected $fillable = [
        'user_id',
        'nombre_publico',
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

    public function payAccount()
    {
        return $this->hasOne(VendedorPaypalAccount::class, 'vendedor_id', 'id');
    }
}
