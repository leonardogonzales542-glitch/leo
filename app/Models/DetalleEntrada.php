<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleEntrada extends Model
{
    protected $table = 'detalle_entradas';

    protected $primaryKey = 'id_detalle_entrada';

    public $timestamps = false;

    protected $fillable = [
        'id_entrada',
        'id_producto',
        'cantidad',
        'precio_compra',
        'subtotal',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function entrada()
    {
        return $this->belongsTo(Entrada::class, 'id_entrada', 'id_entrada');
    }
}
