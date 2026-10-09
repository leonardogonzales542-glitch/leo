<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';

    protected $primaryKey = 'id_cupon';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'descuento',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected $casts = [
        'descuento' => 'decimal:2',
        'estado' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];
}
