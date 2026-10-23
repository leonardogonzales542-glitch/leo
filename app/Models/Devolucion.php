<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devoluciones';

    protected $primaryKey = 'id_devolucion';

    public $timestamps = false;
}
