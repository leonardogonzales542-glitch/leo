<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $primaryKey = 'id_proveedor';

    public $timestamps = false;

    protected $fillable = [
        'razon_social',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'email',
        'direccion',
        'estado',
    ];
}
