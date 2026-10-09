<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false; // Add if you don't have created_at/updated_at in your tables

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];
}
