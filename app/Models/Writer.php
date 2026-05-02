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

    public function payAccount()
    {
        return $this->hasOne(\App\Models\WriterPaypalAccount::class, 'writer_id', 'id');
    }

    public function wallet()
    {
        return $this->hasOne(\App\Models\WriterWallet::class, 'writer_id');
    }

    public function withdrawRequests()
    {
        return $this->hasMany(\App\Models\WithdrawRequest::class, 'writer_id');
    }
}