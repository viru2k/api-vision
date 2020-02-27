<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PracticaDistribucion extends Model
{
    protected $table = 'practica_distribucion';

    protected $fillable = [
        'convenio_os_pmo_id',
        'practica_distribucion_id',
        'porcentaje',
        'valor',
        'total',  
    ];
}
