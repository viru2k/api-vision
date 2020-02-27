<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class LiquidacionGenerada extends Model
{
    protected $table = 'liq_liquidacion_generada';

    protected $fillable = [
        'numero',
        'fecha_liquidacion',               
        'estado',       
    ];
}