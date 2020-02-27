<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Practica extends Model
{
    protected $table = 'practica';

    protected $fillable = [
        'cirugia_grupo_medico_id',
        'convenio_id',
        'fecha_atencion',       
        'paciente_id',
        'cantidad',
        'valor',
        'total_practica',
        'conceptos_debito_id',
        'liquidacion_id',
        'liquidacion_generada_id',
        'es_refacturada',
        'cita_id',
        'usuario_alta_id',
        'usuario_liquida_id',
        'usuario_factura_id',
        'estado_liquidacion',
        'created_at',
        'updated_at',
    ];
}
