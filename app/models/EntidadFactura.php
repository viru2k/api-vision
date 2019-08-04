<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class EntidadFactura extends Model
{
    protected $table = 'entidad';

    protected $fillable = [
        'nombre',           
    ];

}

