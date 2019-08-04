<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\MedicoObraSocial;
use App\models\ConvenioObraSocialPmo;

class ObraSocial extends Model
{
    protected $table = 'obra_social';

    protected $fillable = [
        'nombre',
        'descripcion',
        'es_habilitada',        
        'entidad_factura_id',   
        'tiene_distribucion', 
        'es_coseguro', 
    ];

  
}
