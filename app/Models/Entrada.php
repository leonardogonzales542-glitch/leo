<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    protected $table = 'entradas';
    protected $primaryKey = 'id_entrada';
    public $timestamps = false;
    
    protected $fillable = [
        'id_proveedor',
        'id_usuario',
        'fecha',
        'numero_documento',
        'total',
        'observacion'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleEntrada::class, 'id_entrada', 'id_entrada');
    }
}
