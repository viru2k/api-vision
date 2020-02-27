<?php

namespace App\Http\Controllers\Paciente;

use App\models\PacienteAgenda;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class PacienteAgendaController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res= DB::table('paciente_agendas','cirugia_grupo_medico','agenda_estado','agenda_tipo_atencion','pacientes', 'cirugia_grupo','medicos')
        ->join('pacientes', 'pacientes.id', '=', 'paciente_agendas.paciente_id')
        ->join('cirugia_grupo_medico', 'cirugia_grupo_medico.id', '=', 'paciente_agendas.cirugia_grupo_medico_id')
        ->join('cirugia_grupo', 'cirugia_grupo.id', '=', 'cirugia_grupo_medico.grupo_id')
        ->join('medicos', 'medicos.id', '=', 'cirugia_grupo_medico.medico_id')
        ->join('agenda_estado', 'agenda_estado.id', '=', 'paciente_agendas.agenda_estado_id')
        ->join('agenda_tipo_atencion', 'agenda_tipo_atencion.id', '=', 'paciente_agendas.agenda_tipo_atencion_id')
        ->join('medicos as medico_solicita', 'medico_solicita.id', '=', 'paciente_agendas.medico_solicita_id')
        ->select('paciente_agendas.id',
        'paciente_agendas.fecha_turno',
        'paciente_agendas.fecha_llegada',
        'paciente_agendas.fecha_atendido',
        'paciente_agendas.cirugia_grupo_medico_id as cirugia_grupo_medico_id',
        'paciente_agendas.medico_solicita_id as medico_solicita_id',
        'paciente_agendas.agenda_estado_id as agenda_estado_id',
        'paciente_agendas.agenda_tipo_atencion_id as agenda_tipo_atencion_id',
        'paciente_agendas.usuario_id as usuario_id',
        'cirugia_grupo_medico.codigo',
        'medicos.nombre as nombre',
        'medicos.apellido as apellido',
        'medico_solicita.nombre as medico_solicita_nombre',
        'medico_solicita.apellido as medico_solicita_apellido',
        'pacientes.nombre as paciente_nombre',
        'pacientes.apellido as paciente_apellido',
        'pacientes.dni as paciente_dni',
        'pacientes.domicilio as paciente_domicilio',
        'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
        'cirugia_grupo.nombre as grupo_nombre',
        'agenda_estado.estado as agenda_estado',
        'gravado_adherente'
        )
                 ->get();
                
    return $this->showAll($res);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\PacienteAgenda  $pacienteAgenda
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $res= DB::table('paciente_agendas','cirugia_grupo_medico','agenda_estado','agenda_tipo_atencion','pacientes', 'cirugia_grupo','medicos')
        ->join('pacientes', 'pacientes.id', '=', 'paciente_agendas.paciente_id')
        ->join('cirugia_grupo_medico', 'cirugia_grupo_medico.id', '=', 'paciente_agendas.cirugia_grupo_medico_id')
        ->join('cirugia_grupo', 'cirugia_grupo.id', '=', 'cirugia_grupo_medico.grupo_id')
        ->join('medicos', 'medicos.id', '=', 'cirugia_grupo_medico.medico_id')
        ->join('agenda_estado', 'agenda_estado.id', '=', 'paciente_agendas.agenda_estado_id')
        ->join('agenda_tipo_atencion', 'agenda_tipo_atencion.id', '=', 'paciente_agendas.agenda_tipo_atencion_id')
        ->join('medicos as medico_solicita', 'medico_solicita.id', '=', 'paciente_agendas.medico_solicita_id')
        ->select('paciente_agendas.id',
        'paciente_agendas.fecha_turno',
        'paciente_agendas.fecha_llegada',
        'paciente_agendas.fecha_atendido',
        'paciente_agendas.cirugia_grupo_medico_id as cirugia_grupo_medico_id',
        'paciente_agendas.medico_solicita_id as medico_solicita_id',
        'paciente_agendas.agenda_estado_id as agenda_estado_id',
        'paciente_agendas.agenda_tipo_atencion_id as agenda_tipo_atencion_id',
        'paciente_agendas.usuario_id as usuario_id',
        'cirugia_grupo_medico.codigo',
        'medicos.nombre as medico_nombre',
        'medicos.apellido as medico_apellido',
        'medico_solicita.nombre as medico_solicita_nombre',
        'medico_solicita.apellido as medico_solicita_apellido',
        'pacientes.nombre as paciente_nombre',
        'pacientes.apellido as paciente_apellido',
        'pacientes.dni as paciente_dni',
        'pacientes.domicilio as paciente_domicilio',
        'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
        'cirugia_grupo.nombre as grupo_nombre',
        'agenda_estado.estado as agenda_estado',
        'gravado_adherente'
        )
        ->where('paciente_agendas.id','=', $id)
                 ->get();
                
    return $this->showAll($res);
    }


    public function byDateToday()
    { 
        $res= DB::table('paciente_agendas','cirugia_grupo_medico','agenda_estado','agenda_tipo_atencion','pacientes', 'cirugia_grupo','medicos')
        ->join('pacientes', 'pacientes.id', '=', 'paciente_agendas.paciente_id')
        ->join('cirugia_grupo_medico', 'cirugia_grupo_medico.id', '=', 'paciente_agendas.cirugia_grupo_medico_id')
        ->join('cirugia_grupo', 'cirugia_grupo.id', '=', 'cirugia_grupo_medico.grupo_id')
        ->join('medicos', 'medicos.id', '=', 'cirugia_grupo_medico.medico_id')
        ->join('agenda_estado', 'agenda_estado.id', '=', 'paciente_agendas.agenda_estado_id')
        ->join('agenda_tipo_atencion', 'agenda_tipo_atencion.id', '=', 'paciente_agendas.agenda_tipo_atencion_id')
        ->join('medicos as medico_solicita', 'medico_solicita.id', '=', 'paciente_agendas.medico_solicita_id')
        ->select('paciente_agendas.id',
        'paciente_agendas.fecha_turno',
        'paciente_agendas.fecha_llegada',
        'paciente_agendas.fecha_atendido',
        'paciente_agendas.cirugia_grupo_medico_id as cirugia_grupo_medico_id',
        'paciente_agendas.medico_solicita_id as medico_solicita_id',
        'paciente_agendas.agenda_estado_id as agenda_estado_id',
        'paciente_agendas.agenda_tipo_atencion_id as agenda_tipo_atencion_id',
        'paciente_agendas.usuario_id as usuario_id',
        'cirugia_grupo_medico.codigo',
        'medicos.nombre as medico_nombre',
        'medicos.apellido as medico_apellido',
        'medico_solicita.nombre as medico_solicita_nombre',
        'medico_solicita.apellido as medico_solicita_apellido',
        'pacientes.nombre as paciente_nombre',
        'pacientes.apellido as paciente_apellido',
        'pacientes.dni as paciente_dni',
        'pacientes.domicilio as paciente_domicilio',
        'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
        'cirugia_grupo.nombre as grupo_nombre',
        'agenda_estado.estado as agenda_estado',
        'gravado_adherente'
        )
        ->where('paciente_agendas.fecha_turno','=', date("Y-m-d"))
                 ->get();
                
    return $this->showAll($res);
    echo date('Y-m-d');
    }


    public function byDateSelected($fecha)
    { 
        $res= DB::table('paciente_agendas','cirugia_grupo_medico','agenda_estado','agenda_tipo_atencion','pacientes', 'cirugia_grupo','medicos')
        ->join('pacientes', 'pacientes.id', '=', 'paciente_agendas.paciente_id')
        ->join('cirugia_grupo_medico', 'cirugia_grupo_medico.id', '=', 'paciente_agendas.cirugia_grupo_medico_id')
        ->join('cirugia_grupo', 'cirugia_grupo.id', '=', 'cirugia_grupo_medico.grupo_id')
        ->join('medicos', 'medicos.id', '=', 'cirugia_grupo_medico.medico_id')
        ->join('agenda_estado', 'agenda_estado.id', '=', 'paciente_agendas.agenda_estado_id')
        ->join('agenda_tipo_atencion', 'agenda_tipo_atencion.id', '=', 'paciente_agendas.agenda_tipo_atencion_id')
        ->join('medicos as medico_solicita', 'medico_solicita.id', '=', 'paciente_agendas.medico_solicita_id')
        ->select('paciente_agendas.id',
        'paciente_agendas.fecha_turno',
        'paciente_agendas.fecha_llegada',
        'paciente_agendas.fecha_atendido',
        'paciente_agendas.cirugia_grupo_medico_id as cirugia_grupo_medico_id',
        'paciente_agendas.medico_solicita_id as medico_solicita_id',
        'paciente_agendas.agenda_estado_id as agenda_estado_id',
        'paciente_agendas.agenda_tipo_atencion_id as agenda_tipo_atencion_id',
        'paciente_agendas.usuario_id as usuario_id',
        'cirugia_grupo_medico.codigo',
        'medicos.nombre as medico_nombre',
        'medicos.apellido as medico_apellido',
        'medico_solicita.nombre as medico_solicita_nombre',
        'medico_solicita.apellido as medico_solicita_apellido',
        'pacientes.nombre as paciente_nombre',
        'pacientes.apellido as paciente_apellido',
        'pacientes.dni as paciente_dni',
        'pacientes.domicilio as paciente_domicilio',
        'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
        'cirugia_grupo.nombre as grupo_nombre',
        'agenda_estado.estado as agenda_estado',
        'gravado_adherente'
        )
        ->where('paciente_agendas.fecha_turno','=', $fecha)
                 ->get();
                
    return $this->showAll($res);
    echo date('Y-m-d');
    }

    public function byDni( $dni)
    {
        $res= DB::table('paciente_agendas','cirugia_grupo_medico','agenda_estado','agenda_tipo_atencion','pacientes', 'cirugia_grupo','medicos')
        ->join('pacientes', 'pacientes.id', '=', 'paciente_agendas.paciente_id')
        ->join('cirugia_grupo_medico', 'cirugia_grupo_medico.id', '=', 'paciente_agendas.cirugia_grupo_medico_id')
        ->join('cirugia_grupo', 'cirugia_grupo.id', '=', 'cirugia_grupo_medico.grupo_id')
        ->join('medicos', 'medicos.id', '=', 'cirugia_grupo_medico.medico_id')
        ->join('agenda_estado', 'agenda_estado.id', '=', 'paciente_agendas.agenda_estado_id')
        ->join('agenda_tipo_atencion', 'agenda_tipo_atencion.id', '=', 'paciente_agendas.agenda_tipo_atencion_id')
        ->join('medicos as medico_solicita', 'medico_solicita.id', '=', 'paciente_agendas.medico_solicita_id')
        ->select('paciente_agendas.id',
        'paciente_agendas.fecha_turno',
        'paciente_agendas.fecha_llegada',
        'paciente_agendas.fecha_atendido',
        'paciente_agendas.cirugia_grupo_medico_id as cirugia_grupo_medico_id',
        'paciente_agendas.medico_solicita_id as medico_solicita_id',
        'paciente_agendas.agenda_estado_id as agenda_estado_id',
        'paciente_agendas.agenda_tipo_atencion_id as agenda_tipo_atencion_id',
        'paciente_agendas.usuario_id as usuario_id',
        'cirugia_grupo_medico.codigo',
        'medicos.nombre as medico_nombre',
        'medicos.apellido as medico_apellido',
        'medico_solicita.nombre as medico_solicita_nombre',
        'medico_solicita.apellido as medico_solicita_apellido',
        'pacientes.nombre as paciente_nombre',
        'pacientes.apellido as paciente_apellido',
        'pacientes.dni as paciente_dni',
        'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
        'pacientes.domicilio as paciente_domicilio',
        'cirugia_grupo.nombre as grupo_nombre',
        'agenda_estado.estado as agenda_estado'
        )
        ->where('paciente_agendas.id','=', $dni)
                 ->get();
                
    return $this->showAll($res);
    }
   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\PacienteAgenda  $pacienteAgenda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PacienteAgenda $pacienteAgenda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\PacienteAgenda  $pacienteAgenda
     * @return \Illuminate\Http\Response
     */
    public function destroy(PacienteAgenda $pacienteAgenda)
    {
        //
    }
}
