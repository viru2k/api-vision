<?php

namespace App\Http\Controllers\Practica;

use App\models\Practica;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class PracticaController extends ApiController
{

    // ESTADOS
    /** 
     *  PENDIENTE
     *  AUDITADO
     * 
     * 
     * * */


     /**
      *  facturacion clasificada por obra social / medico / practica(nivel) 
      * 
      */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        
        // FALTA AGREGAR CITA -> AGENDA
        $res= DB::table('practica','cirugia_grupo_medico','cirugia_grupo_medico_factura','pacientes','paciente_agendas','convenio_os_pmo','medicos','obra_social','users')
        ->join('cirugia_grupo_medico','cirugia_grupo_medico.id', '=', 'practica.cirugia_grupo_medico_id')
        ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica.convenio_id' )
        ->join('pacientes', 'pacientes.id', '=', 'practica.paciente_id')
        ->join('paciente_agendas', 'paciente_agendas.id', '=', 'practica.paciente_agendas_id')
        ->join('medicos','medicos.id', '=','cirugia_grupo_medico.medico_id' )
        ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
        ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )
        ->join('users', 'users.id', '=', 'practica.usuario_alta_id')
        ->select(
                 'practica.id',
                 'fecha_atencion',
                 'cirugia_grupo_medico.id as cirugia_medico_id',
                 'cirugia_grupo_medico.medico_id',
                 'medicos.apellido as medico_apellido',
                 'medicos.nombre as medico_nombre',
                 'medicos.id as medico_id',
                 'practica.cantidad as practica_cantidad',
                 'practica.valor as practica_valor',
                 'practica.total_practica as practica_total',
                 'pacientes.nombre as paciente_nombre',
                 'pacientes.apellido as paciente_apellido',
                 'paciente_agendas.id as paciente_agendas_id',
                 'pacientes.dni as paciente_dni',
                 'paciente_agendas.fecha_turno',
                 'paciente_agendas.fecha_atendido',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',
                 'convenio_os_pmo.valor as convenio_valor',
                 'obra_social.nombre as obra_social_nombre',
                 'pmo.codigo as pmo_codigo',
                 'pmo.descripcion as pmo_descripcion',
                 'pmo.complejidad as pmo_nivel',
                 'practica.usuario_alta_id as usuario_alta_id',
                 'users.name'
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
        'cirugia_grupo_medico_id'=> 'required',
        'convenio_id'=> 'required',
        'fecha_atencion'=> 'required',               
        'paciente_id'=> 'required',
        'cantidad'=> 'required',
        'valor'=> 'required',
        'total_practica'=> 'required',                        
        'cita_id'=> 'required',
        'usuario_alta_id'=> 'required',
        ];

        $this->validate($request, $rules);

        $id= DB::table('practica')->insertGetId([
            
            'cirugia_grupo_medico_id' => $request->cirugia_grupo_medico_id,
            'convenio_id' => $request->convenio_id,
            'fecha_atencion' => $request->fecha_atencion,
            'paciente_id' => $request->paciente_id,
            'cantidad' => $request->cantidad,
            'valor' => $request->valor,
            'total_practica' => $request->total_practica,
            'es_refacturada' => 'N',
            'liquidacion_id' => 0,
            'liquidacion_generada_id' => 0,
            'cita_id' => $request->cita_id,
            'estado_liquidacion' => 'PENDIENTE',
            'usuario_alta_id' => $request->usuario_alta_id,
            'usuario_liquida_id' => 0,
            'usuario_factura_id' => 0,
            'usuario_factura_id' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),            

        ]);
        $data = Practica::find($id);
        return $this->showOne($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Practica  $practica
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {

        
        $res= DB::table('practica','cirugia_grupo_medico','cirugia_grupo_medico_factura','pacientes','paciente_agendas','convenio_os_pmo','medicos','obra_social','users')
        ->join('cirugia_grupo_medico','cirugia_grupo_medico.id', '=', 'practica.cirugia_grupo_medico_id')
        ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica.convenio_id' )
        ->join('pacientes', 'pacientes.id', '=', 'practica.paciente_id')
        ->join('paciente_agendas', 'paciente_agendas.id', '=', 'practica.paciente_agendas_id')
        ->join('medicos','medicos.id', '=','cirugia_grupo_medico.medico_id' )
        ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
        ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )
        ->join('users', 'users.id', '=', 'practica.usuario_alta_id')
        ->select(
                 'practica.id',
                 'fecha_atencion',
                 'cirugia_grupo_medico.id as cirugia_medico_id',
                 'cirugia_grupo_medico.medico_id',
                 'medicos.apellido as medico_apellido',
                 'medicos.nombre as medico_nombre',
                 'medicos.id as medico_id',
                 'practica.cantidad as practica_cantidad',
                 'practica.valor as practica_valor',
                 'practica.total_practica as practica_total',
                 'pacientes.nombre as paciente_nombre',
                 'pacientes.apellido as paciente_apellido',
                 'paciente_agendas.id as paciente_agendas_id',
                 'pacientes.dni as paciente_dni',
                 'paciente_agendas.fecha_turno',
                 'paciente_agendas.fecha_atendido',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',
                 'convenio_os_pmo.valor as convenio_valor',
                 'obra_social.nombre as obra_social_nombre',
                 'pmo.codigo as pmo_codigo',
                 'pmo.descripcion as pmo_descripcion',
                 'pmo.complejidad as pmo_nivel',
                 'practica.usuario_alta_id as usuario_alta_id',
                 'users.name'
                 )
        ->where('practica.id','=', $id)
                 ->get();
                
    return $this->showAll($res);
    }


    public function byAgendaId( $paciente_agendas_id )
    {

        
        $res= DB::table('practica','cirugia_grupo_medico','cirugia_grupo_medico_factura','pacientes','paciente_agendas','convenio_os_pmo','medicos','obra_social','users')
        ->join('cirugia_grupo_medico','cirugia_grupo_medico.id', '=', 'practica.cirugia_grupo_medico_id')
        ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica.convenio_id' )
        ->join('pacientes', 'pacientes.id', '=', 'practica.paciente_id')
        ->join('paciente_agendas', 'paciente_agendas.id', '=', 'practica.paciente_agendas_id')
        ->join('medicos','medicos.id', '=','cirugia_grupo_medico.medico_id' )
        ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
        ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )
        ->join('users', 'users.id', '=', 'practica.usuario_alta_id')
        ->select(
                 'practica.id',
                 'fecha_atencion',
                 'cirugia_grupo_medico.id as cirugia_medico_id',
                 'cirugia_grupo_medico.medico_id',
                 'medicos.apellido as medico_apellido',
                 'medicos.nombre as medico_nombre',
                 'medicos.id as medico_id',
                 'practica.cantidad as practica_cantidad',
                 'practica.valor as practica_valor',
                 'practica.total_practica as practica_total',
                 'pacientes.nombre as paciente_nombre',
                 'pacientes.apellido as paciente_apellido',
                 'paciente_agendas.id as paciente_agendas_id',
                 'pacientes.dni as paciente_dni',
                 'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
                 'paciente_agendas.fecha_turno',
                 'paciente_agendas.fecha_atendido',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',                 
                 'convenio_os_pmo.valor as convenio_valor',
                 'obra_social.nombre as obra_social_nombre',
                 'pmo.codigo as pmo_codigo',
                 'pmo.descripcion as pmo_descripcion',
                 'pmo.complejidad as pmo_nivel',
                 'practica.usuario_alta_id as usuario_alta_id',
                 'users.name'
                 )
        ->where('practica.paciente_agendas_id','=', $paciente_agendas_id )
                 ->get();
                
    return $this->showAll($res);
    }

    public function byobrasocial($obra_social_id){

        $res= DB::table('practica','cirugia_grupo_medico','cirugia_grupo_medico_factura','pacientes','convenio_os_pmo','medicos','obra_social','users')
        ->join('cirugia_grupo_medico','cirugia_grupo_medico.id', '=', 'practica.cirugia_grupo_medico_id')
        ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica.convenio_id' )
        ->join('pacientes', 'pacientes.id', '=', 'practica.paciente_id')
        ->join('medicos','medicos.id', '=','cirugia_grupo_medico.medico_id' )
        ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
        ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )
        ->join('users', 'users.id', '=', 'practica.usuario_alta_id')
        ->select(
                 'practica.id',
                 'fecha_atencion',
                 'cirugia_grupo_medico.id as cirugia_medico_id',
                 'cirugia_grupo_medico.medico_id',
                 'medicos.apellido as medico_apellido',
                 'medicos.nombre as medico_nombre',
                 'medicos.id as medico_id',
                 'practica.cantidad as practica_cantidad',
                 'practica.valor as practica_valor',
                 'practica.total_practica as practica_total',
                 'pacientes.nombre as paciente_nombre',
                 'pacientes.apellido as paciente_apellido',
                 'pacientes.dni as paciente_dni',
                 'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',                 
                 'convenio_os_pmo.valor as convenio_valor',
                 'obra_social.nombre as obra_social_nombre',
                 'pmo.codigo as pmo_codigo',
                 'pmo.descripcion as pmo_descripcion',
                 'pmo.complejidad as pmo_nivel',
                 'practica.usuario_alta_id as usuario_alta_id',
                 'users.name'
                 )
        ->where('obra_social.id','=', $obra_social_id)
                 ->get();
                
    return $this->showAll($res);
    }

    public function byObrasocialAndMedico(Request $request){

        $input = $request->all();
        $medico_id = $request->input('medico_id');
        $obra_social_id = $request->input('obra_social_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

         /*  
        var_dump($input);
        die();
        */
        $res= DB::table('practica','cirugia_grupo_medico','cirugia_grupo_medico_factura','pacientes','convenio_os_pmo','medicos','obra_social','users')
        ->join('cirugia_grupo_medico','cirugia_grupo_medico.id', '=', 'practica.cirugia_grupo_medico_id')
        ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica.convenio_id' )
        ->join('pacientes', 'pacientes.id', '=', 'practica.paciente_id')
        ->join('medicos','medicos.id', '=','cirugia_grupo_medico.medico_id' )
        ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
        ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )
        ->join('users', 'users.id', '=', 'practica.usuario_alta_id')
        ->select(
                 'practica.id',
                 'fecha_atencion',
                 'cirugia_grupo_medico.id as cirugia_medico_id',
                 'cirugia_grupo_medico.medico_id',
                 'medicos.apellido as medico_apellido',
                 'medicos.nombre as medico_nombre',
                 'medicos.id as medico_id',
                 'practica.cantidad as practica_cantidad',
                 'practica.valor as practica_valor',
                 'practica.total_practica as practica_total',
                 'pacientes.nombre as paciente_nombre',
                 'pacientes.apellido as paciente_apellido',
                 'pacientes.dni as paciente_dni',
                 'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',                 
                 'convenio_os_pmo.valor as convenio_valor',
                 'obra_social.nombre as obra_social_nombre',
                 'pmo.codigo as pmo_codigo',
                 'pmo.descripcion as pmo_descripcion',
                 'pmo.complejidad as pmo_nivel',
                 'practica.usuario_alta_id as usuario_alta_id',
                 'users.name'
                 )
                 
                ->where([
                    ['cirugia_grupo_medico.id', '=', $medico_id],
                    ['obra_social.id', '=', $obra_social_id],
                ])
                ->whereBetween('fecha_atencion',[$fechaDesde,$fechaHasta])
                ->get();
                
    return $this->showAll($res);
    }


    public function byobrasocialBewteenDates($obra_social_id){

        $res= DB::table('practica','cirugia_grupo_medico','cirugia_grupo_medico_factura','pacientes','convenio_os_pmo','medicos','obra_social','users')
        ->join('cirugia_grupo_medico','cirugia_grupo_medico.id', '=', 'practica.cirugia_grupo_medico_id')
        ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica.convenio_id' )
        ->join('pacientes', 'pacientes.id', '=', 'practica.paciente_id')
        ->join('medicos','medicos.id', '=','cirugia_grupo_medico.medico_id' )
        ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
        ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )
        ->join('users', 'users.id', '=', 'practica.usuario_alta_id')
        ->select(
                 'practica.id',
                 'fecha_atencion',
                 'cirugia_grupo_medico.id as cirugia_medico_id',
                 'cirugia_grupo_medico.medico_id',
                 'medicos.apellido as medico_apellido',
                 'medicos.nombre as medico_nombre',
                 'medicos.id as medico_id',
                 'practica.cantidad as practica_cantidad',
                 'practica.valor as practica_valor',
                 'practica.total_practica as practica_total',
                 'pacientes.nombre as paciente_nombre',
                 'pacientes.apellido as paciente_apellido',
                 'pacientes.dni as paciente_dni',
                 'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',                 
                 'convenio_os_pmo.valor as convenio_valor',
                 'obra_social.nombre as obra_social_nombre',
                 'pmo.codigo as pmo_codigo',
                 'pmo.descripcion as pmo_descripcion',
                 'pmo.complejidad as pmo_nivel',
                 'practica.usuario_alta_id as usuario_alta_id',
                 'users.name'
                 )
        ->where('obra_social.id','=', $obra_social_id)
                 ->get();
                
    return $this->showAll($res);
    }

    public function byMedicoBetweenDates(Request $request){

        $input = $request->all();
        $medico_id = $request->input('medico_id');
        $obra_social_id = $request->input('obra_social_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

         /*  
        var_dump($input);
        die();
        */
        $res= DB::table('practica','cirugia_grupo_medico','cirugia_grupo_medico_factura','pacientes','convenio_os_pmo','medicos','obra_social','users')
        ->join('cirugia_grupo_medico','cirugia_grupo_medico.id', '=', 'practica.cirugia_grupo_medico_id')
        ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica.convenio_id' )
        ->join('pacientes', 'pacientes.id', '=', 'practica.paciente_id')
        ->join('medicos','medicos.id', '=','cirugia_grupo_medico.medico_id' )
        ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
        ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )
        ->join('users', 'users.id', '=', 'practica.usuario_alta_id')
        ->select(
                 'practica.id',
                 'fecha_atencion',
                 'cirugia_grupo_medico.id as cirugia_medico_id',
                 'cirugia_grupo_medico.medico_id',
                 'medicos.apellido as medico_apellido',
                 'medicos.nombre as medico_nombre',
                 'medicos.id as medico_id',
                 'practica.cantidad as practica_cantidad',
                 'practica.valor as practica_valor',
                 'practica.total_practica as practica_total',
                 'pacientes.nombre as paciente_nombre',
                 'pacientes.apellido as paciente_apellido',
                 'pacientes.dni as paciente_dni',
                 'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',                 
                 'convenio_os_pmo.valor as convenio_valor',
                 'obra_social.nombre as obra_social_nombre',
                 'pmo.codigo as pmo_codigo',
                 'pmo.descripcion as pmo_descripcion',
                 'pmo.complejidad as pmo_nivel',
                 'practica.usuario_alta_id as usuario_alta_id',
                 'users.name'
                 )
                 
                ->where([
                    ['cirugia_grupo_medico.id', '=', $medico_id],
                    ['obra_social.id', '=', $obra_social_id],
                ])
                ->whereBetween('fecha_atencion',[$fechaDesde,$fechaHasta])
                ->get();
                
    return $this->showAll($res);
    }



    public function showBetweenDate( Request $request )
    {
        $input = $request->all();     
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
      /*  var_dump($input);
        die();
*/
        $res= DB::table('practica','cirugia_grupo_medico','cirugia_grupo_medico_factura','pacientes','convenio_os_pmo','medicos','obra_social','users')
        ->join('cirugia_grupo_medico','cirugia_grupo_medico.id', '=', 'practica.cirugia_grupo_medico_id')
        ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica.convenio_id' )
        ->join('pacientes', 'pacientes.id', '=', 'practica.paciente_id')
        ->join('medicos','medicos.id', '=','cirugia_grupo_medico.medico_id' )
        ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
        ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )
        ->join('users', 'users.id', '=', 'practica.usuario_alta_id')
        ->select(
                'practica.id',
                'fecha_atencion',
                'cirugia_grupo_medico.id as cirugia_medico_id',
                'cirugia_grupo_medico.medico_id',
                'medicos.apellido as medico_apellido',
                'medicos.nombre as medico_nombre',
                'medicos.id as medico_id',
                'practica.cantidad as practica_cantidad',
                'practica.valor as practica_valor',
                'practica.total_practica as practica_total',
                'pacientes.nombre as paciente_nombre',
                'pacientes.apellido as paciente_apellido',
                'pacientes.dni as paciente_dni',
                'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
                'obra_social.id as obra_social_id',
                'obra_social.id as obra_social_nombre',                
                'convenio_os_pmo.valor as convenio_valor',
                'obra_social.nombre as obra_social_nombre',
                'pmo.codigo as pmo_codigo',
                'pmo.descripcion as pmo_descripcion',
                'pmo.complejidad as pmo_nivel',
                'practica.usuario_alta_id as usuario_alta_id',
                'users.name'
                )
        ->whereBetween('fecha_atencion',[$fechaDesde,$fechaHasta])
        ->get();
                
    return $this->showAll($res);
    }
  


    public function byLiquidacionId($liquidacion_id){

       // $input = $request->all();
      //  $liquidacion_id = $request->input('liquidacion_id');
      
         /*  
        var_dump($input);
        die();
        */
        $res= DB::table('practica','cirugia_grupo_medico','cirugia_grupo_medico_factura','pacientes','convenio_os_pmo','medicos','obra_social','users','liq_liquidacion')
        ->join('cirugia_grupo_medico','cirugia_grupo_medico.id', '=', 'practica.cirugia_grupo_medico_id')
        ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica.convenio_id' )
        ->join('pacientes', 'pacientes.id', '=', 'practica.paciente_id')
        ->join('medicos','medicos.id', '=','cirugia_grupo_medico.medico_id' )
        ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
        ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )
        ->join('users', 'users.id', '=', 'practica.usuario_alta_id')
        ->join('liq_liquidacion','liq_liquidacion.id', '=', 'practica.liquidacion_id')
        ->select(
                 'practica.id',
                 'fecha_atencion',
                 'cirugia_grupo_medico.id as cirugia_medico_id',
                 'cirugia_grupo_medico.medico_id',
                 'medicos.apellido as medico_apellido',
                 'medicos.nombre as medico_nombre',
                 'medicos.id as medico_id',
                 'practica.cantidad as practica_cantidad',
                 'practica.valor as practica_valor',
                 'practica.total_practica as practica_total',
                 'pacientes.nombre as paciente_nombre',
                 'pacientes.apellido as paciente_apellido',
                 'pacientes.dni as paciente_dni',
                 'pacientes.fecha_nacimiento as paciente_fecha_nacimiento',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',                 
                 'convenio_os_pmo.valor as convenio_valor',
                 'obra_social.nombre as obra_social_nombre',
                 'pmo.codigo as pmo_codigo',
                 'pmo.descripcion as pmo_descripcion',
                 'pmo.complejidad as pmo_nivel',
                 'practica.usuario_alta_id as usuario_alta_id',
                 'users.name',
                 'practica.liquidacion_id as liquidacion_id',
                 'liq_liquidacion.numero as liquidacion_numero',
                 'liq_liquidacion.usuario_audito  as usuario_audito '
                 )
                 
                ->where('practica.liquidacion_id', '=', $liquidacion_id)
                  
                ->get();
                
    return $this->showAll($res);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Practica  $practica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $practica = Practica::findOrFail($id);
        $practica->fill($request->only([
            'cirugia_grupo_medico_id',
            'convenio_id',
            'fecha_atencion',               
            'paciente_id',
            'cantidad',
            'es_refacturada',
            'liquidacion_id',            
            'liquidacion_generada_id',
            'valor',
            'total_practica',                        
            'cita_id',
            'usuario_alta_id',
            'estado_liquidacion',
            'usuario_alta',
            'usuario_liquida_id',
            'usuario_factura_id'
    ]));

   if ($practica->isClean()) {
    return $this->errorResponse('Se debe especificar al menos un valor', 422);
    }
   $practica->save();
    return $this->showOne($practica);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Practica  $practica
     * @return \Illuminate\Http\Response
     */
    public function destroy(Practica $practica)
    {
        //
    }
}
