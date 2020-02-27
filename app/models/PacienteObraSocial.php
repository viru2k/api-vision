<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PacienteObraSocial extends Model
{
    protected $table = 'paciente_obra_sociales';

    protected $fillable = [
            'paciente_id',
            'obra_social_id',
            'obra_social_numero',               
            'coseguro_id',
            'barra',
    ];
}
