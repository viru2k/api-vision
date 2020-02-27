<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PacienteAgenda extends Model
{
    protected $table = 'paciente_agendas';

    protected $fillable = [
        
         'paciente_id',
         'fecha_turno',
         'fecha_llegada',
         'fecha_atendido',
         'cirugia_grupo_medico_id',
         'medico_solicita_id',
         'agenda_estado_id',
         'agenda_tipo_atencion_id',
         'usuario_id',
     ];
}
