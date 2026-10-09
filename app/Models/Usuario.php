<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    
    public const CREATED_AT = 'fecha_creacion';
    public const UPDATED_AT = null;

    protected $fillable = [
        'usuario',
        'email',
        'password',
        'id_rol',
        'estado'
    ];

    protected $hidden = [
        'password',
    ];
}

