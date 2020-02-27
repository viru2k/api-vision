<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liq_liquidacion';

    protected $fillable = [
       
        'obra_social_id',
        'numero',
        'fecha_desde',               
        'fecha_hasta',
        'liquidacion_generada_id',
        'cant_orden',
        'total',
        'estado',
        'usuario_audito'
    ];
}