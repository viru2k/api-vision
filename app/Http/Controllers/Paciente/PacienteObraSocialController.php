<?php

namespace App\Http\Controllers\Paciente;

use App\models\PacienteObraSocial;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class PacienteObraSocialController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        // FALTA AGREGAR CITA -> AGENDA
        $res= DB::table('paciente_obra_sociales','pacientes', 'obra_social')
        ->join('pacientes','pacientes.id', '=', 'paciente_obra_sociales.paciente_id')
        
        ->join('obra_social','obra_social.id', '=', 'paciente_obra_sociales.obra_social_id')
        ->join('obra_social as coseguro','coseguro.id', '=', 'paciente_obra_sociales.coseguro_id')
       // ->join('users', 'users.id', '=', 'pacientes.usuario_alta_id')
        ->select(
                'paciente_obra_sociales.id',
                'obra_social_numero',
                'barra',
                'pacientes.nombre as paciente_nombre',
                'pacientes.apellido as paciente_apellido',
                'pacientes.dni as paciente_dni',
                'obra_social.id as obra_social_id',
               'obra_social.nombre as obra_social_nombre',            
               'obra_social.tiene_distribucion',
               'obra_social.es_coseguro',
               'obra_social.es_habilitada',
                'coseguro.nombre as coseguro_nombre',
                'coseguro.id as coseguro_id'                     
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
        $rules = [
            'paciente_id'=> 'required',
            'obra_social_id'=> 'required',
            'obra_social_numero'=> 'required',               
            'coseguro_id'=> 'required',
            'barra'=> 'required',
            ];
    
            $this->validate($request, $rules);
    
            $id= DB::table('paciente_obra_sociales')->insertGetId([
                
                'paciente_id' => $request->paciente_id,
                'obra_social_id' => $request->obra_social_id,
                'obra_social_numero' => $request->obra_social_numero,
                'coseguro_id' => $request->coseguro_id,
                'barra' => $request->barra,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),        
                'obra_social_old_id' => 0,    
                'coseguro_old_id' =>0,
    
            ]);
            $data = PacienteObraSocial::find($id);
            return $this->showOne($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\PacienteObraSocial  $pacienteObraSocial
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $res= DB::table('paciente_obra_sociales','pacientes', 'obra_social')
        ->join('pacientes','pacientes.id', '=', 'paciente_obra_sociales.paciente_id')
        
        ->join('obra_social','obra_social.id', '=', 'paciente_obra_sociales.obra_social_id')
        ->join('obra_social as coseguro','coseguro.id', '=', 'paciente_obra_sociales.coseguro_id')
       // ->join('users', 'users.id', '=', 'pacientes.usuario_alta_id')
        ->select(
                'paciente_obra_sociales.id',
                'obra_social_numero',
                'barra',
                'pacientes.nombre as paciente_nombre',
                'pacientes.apellido as paciente_apellido',
                'pacientes.dni as paciente_dni',
                'obra_social.id as obra_social_id',
               'obra_social.nombre as obra_social_nombre',            
               'obra_social.tiene_distribucion',
               'obra_social.es_coseguro',
               'obra_social.es_habilitada',
                'coseguro.nombre as coseguro_nombre',
                'coseguro.id as coseguro_id'                     
                 )
                 ->where('paciente_obra_sociales.id','=', $id)
                 ->get();
                 return $this->showAll($res);
                
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\PacienteObraSocial  $pacienteObraSocial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pacienteObraSocial = PacienteObraSocial::findOrFail($id);
            $pacienteObraSocial->fill($request->only([
                'paciente_id',
                'obra_social_id',
                'obra_social_numero',               
                'obra_social_old_id',
                'coseguro_id',
                'coseguro_old_id',
                'barra',
                'updated_at',
                'created_at'
        ]));
    
       if ($pacienteObraSocial->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor', 422);
        }
       $pacienteObraSocial->save();
        return $this->showOne($pacienteObraSocial);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\PacienteObraSocial  $pacienteObraSocial
     * @return \Illuminate\Http\Response
     */
    public function destroy(PacienteObraSocial $pacienteObraSocial)
    {
        //
    }
}
