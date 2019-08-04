<?php

namespace App\Http\Controllers\OperacionCobro;

use App\models\OperacionCobro;
use App\models\OperacionCobroPractica;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class OperacionCobroController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $id= DB::table('operacion_cobro')->insertGetId([
            'obra_social_id' => $request->obra_social_id,
            'paciente_id' => $request->paciente_id,
            'total_operacion_cobro' => $request->total_operacion_cobro,
            'total_facturado' => $request->total_facturado,
            'total_coseguro' => $request->total_coseguro,
            'total_honorarios_medicos' => $request->total_honorarios_medicos,
            'total_otros' => $request->total_otros,
            'usuario_cobro_id' => $request->usuario_cobro_id,
            'usuario_audita_id' => $request->usuario_audita_id,
            'fecha_cobro' => $request->fecha_cobro,
            'estado' => $request->estado,
            'observacion' => $request->observacion,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    

        ]);

        DB::table('operacion_cobro_medico')->insertGetId([
            'operacion_cobro_id' => $id,
            'medico_factura_id' => $request->medico_factura_id,
            'practica_nivel' => $request->practica_nivel,          
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    

        ]);      
        $resp = OperacionCobro::find($id);
        
    }






    public function registroOperacionCobro(Request $request)
{
        $t = $request->operacionCobroPractica;
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_cobro'));
        $fecha_cobro =  date('Y-m-d H:i:s', strtotime($tmp_fecha));   
    // nivel para validar si debo insertar distribucion o no
        $nivel = 0;
     try {
    $id= DB::table('operacion_cobro')->insertGetId([
        'obra_social_id' => $request->obra_social_id,
        'paciente_id' => $request->paciente_id,
        'total_operacion_cobro' => $request->total_operacion_cobro,
        'total_facturado' => $request->total_facturado,
        'total_coseguro' => $request->total_coseguro,
        'total_honorarios_medicos' => $request->total_honorarios_medicos,
        'total_otros' => $request->total_otros,
        'usuario_cobro_id' => $request->usuario_cobro_id,
        'usuario_audita_id' => $request->usuario_audita_id,
        'fecha_cobro' => $fecha_cobro,
        'observacion' => $request->observacion,        
        'numero_bono' => $request->numero_bono,     
        'liquidacion_numero' => 0,
        'estado' => $request->estado,
        'es_anulado' =>$request->operacion_cobro_es_anulado,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")    
    ]);
    //var_dump($data["operacionCobroMedico"]);

    DB::table('operacion_cobro_medico')->insertGetId([
        'operacion_cobro_id' => $id,
        'medico_factura_id' => $request->operacionCobroMedico["medico_factura_id"],
        'practica_nivel' => $request->operacionCobroMedico["practica_nivel"],          
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")    
    ]);      

        $categorizacion = 0;

    foreach ($t as $res) {
      //  var_dump($res["valor_original"]);
      // SI LA OBRA SOCIAL ES 1 ASIGNO CATEGORIZACION  DE OBRA SOCIAL, SINO DE COSEGURO
      if($res["obra_social_id"] == 1){
        $categorizacion = $res["total_honorario_obra_social"];
      }else{
        $categorizacion = $res["total_honorario_coseguro"];
      }
        $nivel = $res["pmo_nivel"];
        DB::table('operacion_cobro_practica')->insertGetId([            
            'convenio_os_pmo_id' => $res["convenio_os_pmo_id"],
            'valor_original' => $res["valor_original"],          
            'valor_facturado' => $res["valor_facturado"],       
            'coeficiente' => 1,      
            'forma_pago' => $res["forma_pago"],
            'categorizacion' => $categorizacion,//$res["categorizacion"],
            'operacion_cobro_id' => $id,       
            'paciente_id' => $request->paciente_id, 
            'user_medico_id' => $request->operacionCobroMedico["medico_factura_id"], 
            'observacion' =>  $res["observacion"],                  
            'usuario_audita_id' => 1,
            'usuario_realiza_id' => $res["usuario_realiza_id"],
            'cantidad' =>  $res["cantidad"],  
            'estado_liquidacion' => 'PEN',
            'estado_facturacion' => 'P',
            'liquidacion_numero' => 0,
            'es_anulado' =>  $res["es_anulado"],
            'internacion_tipo' => $res["internacion_tipo"],
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);      
            $categorizacion = 0;
    }
  
    $t_distribucion = $request->practica_distribucion;
    if (isset($t_distribucion[0]['id'])){
    foreach ($t_distribucion as $res) {
     
        //  var_dump($res["valor_original"]);
          DB::table('operacion_cobro_distribucion')->insertGetId([            
            'practica_distribucion_id' => $res["id"],  
            'obra_social_practica_nombre' => $res["obra_social_practica_nombre"],   
            'operacion_cobro_id' => $id,
            'convenio_os_pmo_id' => $res["convenio_os_pmo_id"],          
            'porcentaje' => $res["practica_distribucion_porcentaje"],                          
              'valor' => $res["practica_distribucion_valor"],
              'total' => $res["practica_distribucion_total"],                           
              'created_at' => date("Y-m-d H:i:s"),
              'updated_at' => date("Y-m-d H:i:s")    
          ]);      
   
      } 
    }
    }catch (\Exception $e) {
         $e->getMessage();
      //  return $this->showOne($e, 500);
        return response()->json($e, 500);
    }
        $resp = OperacionCobro::find($id); 
        return response()->json($resp, 201);        
    //  return response()->json($t_distribucion, 201);   
        //return $resp["usuario_cobro_id"]; 
}



    /*********************NO SE A REALIZADO CONSULTA , ESTA SOLAMENTE PARA NO OLVIDARSE DE HACER********************* */
    public function updateValoresDistribucion(Request $request)
    {       
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
        $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha));         
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
        $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));  
        $estado_liquidacion =$request->input('estado_liquidacion');

        $horario = DB::statement((" 
        UPDATE operacion_cobro_practica,operacion_cobro ,convenio_os_pmo,pmo, obra_social SET operacion_cobro_practica.valor_facturado = convenio_os_pmo.valor * operacion_cobro_practica.cantidad WHERE operacion_cobro.id = operacion_cobro_practica.operacion_cobro_id AND operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND  obra_social.id = convenio_os_pmo.obra_social_id AND operacion_cobro_practica.estado_liquidacion IN('PEN','AUD')  AND obra_social_id= :obra_social_id  AND  operacion_cobro.fecha_cobro BETWEEN :fecha_desde AND :fecha_hasta          
    "), array(
        'fecha_desde' =>$fecha_desde,
        'fecha_hasta' => $fecha_hasta,
        'obra_social_id' => $obra_social_id,
        'estado_liquidacion' => $estado_liquidacion    
      ));

             return response()->json($horario, 201);         
    }


    public function updateOperacionCobroRecalcularValoresBetweenDates(Request $request)
    {       
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
        $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha));         
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
        $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));  
        $estado_liquidacion =$request->input('estado_liquidacion');

        $horario = DB::statement((" 
        UPDATE operacion_cobro_practica,operacion_cobro ,convenio_os_pmo,pmo, obra_social SET operacion_cobro_practica.valor_facturado = convenio_os_pmo.valor * operacion_cobro_practica.cantidad WHERE operacion_cobro.id = operacion_cobro_practica.operacion_cobro_id AND operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND  obra_social.id = convenio_os_pmo.obra_social_id AND operacion_cobro_practica.estado_liquidacion = :estado_liquidacion AND  operacion_cobro.fecha_cobro BETWEEN :fecha_desde AND :fecha_hasta          
    "), array(
        'fecha_desde' =>$fecha_desde,
        'fecha_hasta' => $fecha_hasta,
        'estado_liquidacion' => $estado_liquidacion    
      ));

             return response()->json($horario, 201);         
    }


    public function updateOperacionCobroRecalcularAfectadosValoresBetweenDates(Request $request)
    {       
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
        $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha));         
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
        $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));  
        $estado_liquidacion =$request->input('estado_liquidacion');

        $horario = DB::statement((" 
        UPDATE operacion_cobro_practica,operacion_cobro ,convenio_os_pmo,pmo, obra_social SET operacion_cobro_practica.valor_facturado = convenio_os_pmo.valor * operacion_cobro_practica.cantidad WHERE operacion_cobro.id = operacion_cobro_practica.operacion_cobro_id AND operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND  obra_social.id = convenio_os_pmo.obra_social_id AND operacion_cobro_practica.estado_liquidacion = :estado_liquidacion AND  operacion_cobro.fecha_cobro BETWEEN :fecha_desde AND :fecha_hasta          
    "), array(
        'fecha_desde' =>$fecha_desde,
        'fecha_hasta' => $fecha_hasta,
        'estado_liquidacion' => $estado_liquidacion    
      ));

             return response()->json($horario, 201);         
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\operacionCobro  $operacionCobro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res= DB::table('operacion_cobro','paciente','obra_social','users')
        ->join('obra_social', 'obra_social.id', '=', 'operacion_cobro.obra_social_id')
        ->join('paciente', 'paciente.id', '=', 'operacion_cobro.paciente_id')
        ->join('users', 'users.id', '=', 'operacion_cobro.usuario_cobro_id')
        ->join('users as usuario_audita', 'usuario_audita.id', '=', 'operacion_cobro.usuario_audita_id')
        ->select('operacion_cobro.id',       
        'paciente.nombre',
        'paciente.apellido',
        'paciente.dni',
        'paciente.fecha_nacimiento',
        'operacion_cobro.total_operacion_cobro',
        'operacion_cobro.total_facturado',
        'operacion_cobro.total_coseguro', 
        'operacion_cobro.total_honorarios_medicos', 
        'operacion_cobro.obra_social_id',
        'operacion_cobro.observacion',
        'obra_social.nombre as obra_social_nombre',        
        'operacion_cobro.usuario_cobro_id',
        'users.nombreyapellido as usuario_cobro_nombreyapellido',        
       'operacion_cobro.usuario_audita_id',
        'usuario_audita.nombreyapellido as usuario_audita_nombreyapellido'
        )
        ->where('operacion_cobro.id','=', $id)
        ->get();
    return $this->showAll($res);
    }
   

    public function getOperacionCobroRegistrosBetweenDates(Request $request)
    {
           
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
        $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha));         
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
        $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));  
        $estado_liquidacion =$request->input('estado_liquidacion');

        $horario = DB::select( DB::raw("SELECT operacion_cobro_practica.id , operacion_cobro.id as operacion_cobro_id, convenio_os_pmo.id as pmo_id,forma_pago, 
        operacion_cobro_practica.valor_facturado, operacion_cobro_practica.valor_facturado as distribucion, operacion_cobro_practica.valor_original, operacion_cobro_practica.categorizacion, operacion_cobro_practica.cantidad,
        operacion_cobro_practica.observacion,operacion_cobro_practica.user_medico_id,operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre, paciente.dni,
        obra_social.nombre as obra_social_nombre, obra_social.es_coseguro,  pmo.codigo, pmo.descripcion,   pmo.complejidad   , users.nombreyapellido as medico_nombre,
        operacion_cobro_practica.liquidacion_numero, operacion_cobro_practica.estado_liquidacion, estado_facturacion, usuario_realiza_id , usuario_cobro.nombreyapellido as usuario_cobro_nombre,
        usuario_cobro.id as usuario_cobro_id,  paciente.id as paciente_id ,paciente.numero_afiliado ,paciente.barra_afiliado,   paciente.gravado_adherente , obra_social.id as obra_social_id, convenio_os_pmo.id as convenio_os_pmo_id,
        operacion_cobro.obra_social_id as operacion_cobro_obra_social_id, operacion_cobro_practica.usuario_audita_id, operacion_cobro.total_operacion_cobro as operacion_cobro_total_operacion_cobro, operacion_cobro.total_facturado as operacion_cobro_total_facturado,
        operacion_cobro.total_coseguro as operacion_cobro_total_coseguro, operacion_cobro.total_honorarios_medicos as operacion_cobro_total_honorarios_medicos, operacion_cobro.total_otros as operacion_cobro_total_otros,
        operacion_cobro.fecha_cobro as operacion_cobro_fecha_cobro, operacion_cobro.numero_bono as operacion_cobro_numero_bono, operacion_cobro.liquidacion_numero as operacion_cobro_liquidacion_numero,
        operacion_cobro.estado as operacion_cobro_estado, operacion_cobro_obra_social.nombre as operacion_cobro_obra_social_nombre,
        CONCAT(operacion_cobro_paciente.apellido,' ' , operacion_cobro_paciente.nombre) as  operacion_cobro_paciente_nombre,  operacion_cobro_practica.tiene_observacion, operacion_cobro_practica.motivo_observacion, operacion_cobro.es_anulado as operacion_cobro_es_anulado, operacion_cobro_practica.es_anulado,internacion_tipo
        FROM users as usuario_cobro, obra_social as operacion_cobro_obra_social , paciente as operacion_cobro_paciente,operacion_cobro_practica
        LEFT JOIN operacion_cobro 
        ON  operacion_cobro_practica.operacion_cobro_id = operacion_cobro.id , paciente, convenio_os_pmo, pmo,users, obra_social WHERE operacion_cobro_practica.paciente_id = paciente.id AND operacion_cobro.paciente_id =  operacion_cobro_paciente.id AND  operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id AND convenio_os_pmo.obra_social_id = obra_social.id  AND usuario_cobro.id = usuario_realiza_id  AND estado_facturacion = 'P' AND estado_liquidacion= :estado_liquidacion  AND operacion_cobro_obra_social.id = operacion_cobro.obra_social_id AND operacion_cobro_practica.es_anulado = 'NO' AND  operacion_cobro.fecha_cobro BETWEEN :fecha_desde AND :fecha_hasta 
    "), array(
        'fecha_desde' =>$fecha_desde,
        'fecha_hasta' => $fecha_hasta,
        'estado_liquidacion' => $estado_liquidacion    
      ));
       
      return response()->json($horario, 201);
    }


    
    public function getOperacionCobroRegistrosBetweenDatesAndMedico(Request $request)
    {
        $user_medico_id = $request->input('user_medico_id');
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
        $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha));         
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
        $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));  
        $estado_liquidacion =$request->input('estado_liquidacion');

        $horario = DB::select( DB::raw("SELECT operacion_cobro_practica.id , operacion_cobro.id as operacion_cobro_id, convenio_os_pmo.id as pmo_id,forma_pago, 
        operacion_cobro_practica.valor_facturado, operacion_cobro_practica.valor_facturado as distribucion, operacion_cobro_practica.valor_original, operacion_cobro_practica.categorizacion, operacion_cobro_practica.cantidad,
        operacion_cobro_practica.observacion,operacion_cobro_practica.user_medico_id,operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre, paciente.dni,
        obra_social.nombre as obra_social_nombre, obra_social.es_coseguro, pmo.codigo, pmo.descripcion,   pmo.complejidad   , users.nombreyapellido as medico_nombre,
        operacion_cobro_practica.liquidacion_numero, operacion_cobro_practica.estado_liquidacion, estado_facturacion, usuario_realiza_id , usuario_cobro.nombreyapellido as usuario_cobro_nombre,
        usuario_cobro.id as usuario_cobro_id,  paciente.id as paciente_id ,paciente.numero_afiliado ,paciente.barra_afiliado, paciente.gravado_adherente, obra_social.id as obra_social_id, convenio_os_pmo.id as convenio_os_pmo_id,
        operacion_cobro.obra_social_id as operacion_cobro_obra_social_id, operacion_cobro_practica.usuario_audita_id, operacion_cobro.total_operacion_cobro as operacion_cobro_total_operacion_cobro, operacion_cobro.total_facturado as operacion_cobro_total_facturado,
        operacion_cobro.total_coseguro as operacion_cobro_total_coseguro, operacion_cobro.total_honorarios_medicos as operacion_cobro_total_honorarios_medicos, operacion_cobro.total_otros as operacion_cobro_total_otros, operacion_cobro.fecha_cobro as operacion_cobro_fecha_cobro,
        operacion_cobro.numero_bono as operacion_cobro_numero_bono, operacion_cobro.liquidacion_numero as operacion_cobro_liquidacion_numero, operacion_cobro.estado as operacion_cobro_estado, operacion_cobro_obra_social.nombre as operacion_cobro_obra_social_nombre,
        CONCAT(operacion_cobro_paciente.apellido,' ' , operacion_cobro_paciente.nombre) as  operacion_cobro_paciente_nombre,  operacion_cobro_practica.tiene_observacion, operacion_cobro_practica.motivo_observacion, operacion_cobro.es_anulado as operacion_cobro_es_anulado, operacion_cobro_practica.es_anulado, internacion_tipo
        FROM users as usuario_cobro, obra_social as operacion_cobro_obra_social , paciente as operacion_cobro_paciente,operacion_cobro_practica
        LEFT JOIN operacion_cobro 
        ON  operacion_cobro_practica.operacion_cobro_id = operacion_cobro.id , paciente, convenio_os_pmo, pmo,users, obra_social WHERE operacion_cobro_practica.paciente_id = paciente.id AND operacion_cobro.paciente_id =  operacion_cobro_paciente.id AND  operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id AND convenio_os_pmo.obra_social_id = obra_social.id  AND usuario_cobro.id = usuario_realiza_id  AND estado_facturacion = 'P' AND estado_liquidacion= :estado_liquidacion  AND operacion_cobro_obra_social.id = operacion_cobro.obra_social_id AND operacion_cobro_practica.es_anulado = 'NO' AND operacion_cobro_practica.user_medico_id= ".$user_medico_id." AND  operacion_cobro.fecha_cobro BETWEEN :fecha_desde AND :fecha_hasta 
    "), array(
        'fecha_desde' =>$fecha_desde,
        'fecha_hasta' => $fecha_hasta,
        'estado_liquidacion' => $estado_liquidacion    
      ));
       
      return response()->json($horario, 201);
    }


    
    public function getOperacionCobroRegistrosById(Request $request)
    {
        $id = $request->input('id');
        $estado_liquidacion =$request->input('estado_liquidacion');

        $horario = DB::select( DB::raw("SELECT operacion_cobro_practica.id , operacion_cobro.id as operacion_cobro_id, convenio_os_pmo.id as pmo_id,forma_pago, 
        operacion_cobro_practica.valor_facturado, operacion_cobro_practica.valor_facturado as distribucion, operacion_cobro_practica.valor_original, operacion_cobro_practica.categorizacion, operacion_cobro_practica.cantidad,
        operacion_cobro_practica.observacion,operacion_cobro_practica.user_medico_id,operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre, paciente.dni,
        obra_social.nombre as obra_social_nombre, obra_social.es_coseguro, pmo.codigo, pmo.descripcion,   pmo.complejidad   , users.nombreyapellido as medico_nombre,
        operacion_cobro_practica.liquidacion_numero, operacion_cobro_practica.estado_liquidacion, estado_facturacion, usuario_realiza_id , usuario_cobro.nombreyapellido as usuario_cobro_nombre,
        usuario_cobro.id as usuario_cobro_id,  paciente.id as paciente_id ,paciente.numero_afiliado ,paciente.barra_afiliado, paciente.gravado_adherente, obra_social.id as obra_social_id, convenio_os_pmo.id as convenio_os_pmo_id, operacion_cobro.obra_social_id as operacion_cobro_obra_social_id,
        operacion_cobro_practica.usuario_audita_id, operacion_cobro.total_operacion_cobro as operacion_cobro_total_operacion_cobro, operacion_cobro.total_facturado as operacion_cobro_total_facturado, operacion_cobro.total_coseguro as operacion_cobro_total_coseguro,
        operacion_cobro.total_honorarios_medicos as operacion_cobro_total_honorarios_medicos, operacion_cobro.total_otros as operacion_cobro_total_otros, operacion_cobro.fecha_cobro as operacion_cobro_fecha_cobro, operacion_cobro.numero_bono as operacion_cobro_numero_bono, 
        operacion_cobro.liquidacion_numero as operacion_cobro_liquidacion_numero, operacion_cobro.estado as operacion_cobro_estado, operacion_cobro_obra_social.nombre as operacion_cobro_obra_social_nombre,
        CONCAT(operacion_cobro_paciente.apellido,' ' , operacion_cobro_paciente.nombre) as  operacion_cobro_paciente_nombre, operacion_cobro_practica.tiene_observacion, operacion_cobro_practica.motivo_observacion, operacion_cobro.es_anulado as operacion_cobro_es_anulado, operacion_cobro_practica.es_anulado, internacion_tipo
        FROM users as usuario_cobro, obra_social as operacion_cobro_obra_social , paciente as operacion_cobro_paciente,operacion_cobro_practica
        LEFT JOIN operacion_cobro 
        ON  operacion_cobro_practica.operacion_cobro_id = operacion_cobro.id , paciente, convenio_os_pmo, pmo,users, obra_social WHERE operacion_cobro_practica.paciente_id = paciente.id AND operacion_cobro.paciente_id =  operacion_cobro_paciente.id AND  operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id AND convenio_os_pmo.obra_social_id = obra_social.id  AND usuario_cobro.id = usuario_realiza_id  AND estado_facturacion = 'P' AND estado_liquidacion= :estado_liquidacion  AND operacion_cobro_obra_social.id = operacion_cobro.obra_social_id AND operacion_cobro_practica.operacion_cobro_id= ".$id."
    "), array(
        'estado_liquidacion' => $estado_liquidacion     
      ));
       
      return response()->json($horario, 201);
    }



    
    public function getOperacionCobroRegistrosBetweenDatesAnulado(Request $request)
    {
        $user_medico_id = $request->input('user_medico_id');
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
        $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha));         
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
        $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));  
        $estado_liquidacion =$request->input('estado_liquidacion');

        $horario = DB::select( DB::raw("SELECT operacion_cobro_practica.id , operacion_cobro.id as operacion_cobro_id, convenio_os_pmo.id as pmo_id,forma_pago, 
        operacion_cobro_practica.valor_facturado, operacion_cobro_practica.valor_facturado as distribucion, operacion_cobro_practica.valor_original, operacion_cobro_practica.categorizacion, operacion_cobro_practica.cantidad,
        operacion_cobro_practica.observacion,operacion_cobro_practica.user_medico_id,operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre, paciente.dni,
        obra_social.nombre as obra_social_nombre, pmo.codigo, pmo.descripcion,   pmo.complejidad   , users.nombreyapellido as medico_nombre,
        operacion_cobro_practica.liquidacion_numero, operacion_cobro_practica.estado_liquidacion, estado_facturacion, usuario_realiza_id , usuario_cobro.nombreyapellido as usuario_cobro_nombre,
        usuario_cobro.id as usuario_cobro_id,  paciente.id as paciente_id ,paciente.numero_afiliado ,paciente.barra_afiliado, paciente.gravado_adherente, obra_social.id as obra_social_id, convenio_os_pmo.id as convenio_os_pmo_id,
        operacion_cobro.obra_social_id as operacion_cobro_obra_social_id, operacion_cobro_practica.usuario_audita_id, operacion_cobro.total_operacion_cobro as operacion_cobro_total_operacion_cobro, operacion_cobro.total_facturado as operacion_cobro_total_facturado,
        operacion_cobro.total_coseguro as operacion_cobro_total_coseguro, operacion_cobro.total_honorarios_medicos as operacion_cobro_total_honorarios_medicos, operacion_cobro.total_otros as operacion_cobro_total_otros, operacion_cobro.fecha_cobro as operacion_cobro_fecha_cobro,
        operacion_cobro.numero_bono as operacion_cobro_numero_bono, operacion_cobro.liquidacion_numero as operacion_cobro_liquidacion_numero, operacion_cobro.estado as operacion_cobro_estado, operacion_cobro_obra_social.nombre as operacion_cobro_obra_social_nombre,
        CONCAT(operacion_cobro_paciente.apellido,' ' , operacion_cobro_paciente.nombre) as  operacion_cobro_paciente_nombre,  operacion_cobro_practica.tiene_observacion, operacion_cobro_practica.motivo_observacion, operacion_cobro.es_anulado as operacion_cobro_es_anulado, operacion_cobro_practica.es_anulado,internacion_tipo
        FROM users as usuario_cobro, obra_social as operacion_cobro_obra_social , paciente as operacion_cobro_paciente,operacion_cobro_practica
        LEFT JOIN operacion_cobro 
        ON  operacion_cobro_practica.operacion_cobro_id = operacion_cobro.id , paciente, convenio_os_pmo, pmo,users, obra_social WHERE operacion_cobro_practica.paciente_id = paciente.id AND operacion_cobro.paciente_id =  operacion_cobro_paciente.id AND  operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id AND convenio_os_pmo.obra_social_id = obra_social.id  AND usuario_cobro.id = usuario_realiza_id  AND estado_facturacion = 'P' AND estado_liquidacion= :estado_liquidacion  AND operacion_cobro_obra_social.id = operacion_cobro.obra_social_id AND operacion_cobro_practica.es_anulado = 'SI' AND operacion_cobro_practica.user_medico_id= ".$user_medico_id." AND  operacion_cobro.fecha_cobro BETWEEN :fecha_desde AND :fecha_hasta 
    "), array(
        'fecha_desde' =>$fecha_desde,
        'fecha_hasta' => $fecha_hasta,
        'estado_liquidacion' => $estado_liquidacion    
      ));
       
      return response()->json($horario, 201);
    }



    public function getOperacionCobroRegistroDistribucionById(Request $request)
    {
        $id = $request->input('id');
        $estado_liquidacion =$request->input('estado_liquidacion');
        $obra_social_id =$request->input('obra_social_id');

        $horario = DB::select( DB::raw("  
        SELECT operacion_cobro_practica.id , operacion_cobro.id as operacion_cobro_id, convenio_os_pmo.id as pmo_id,forma_pago, 
        operacion_cobro_practica.valor_facturado, operacion_cobro_practica.valor_facturado as distribucion, operacion_cobro_practica.valor_original, operacion_cobro_practica.categorizacion, operacion_cobro_practica.cantidad,
        operacion_cobro_practica.observacion,operacion_cobro_practica.user_medico_id,operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre, paciente.dni,
        obra_social.nombre as obra_social_nombre, pmo.codigo, pmo.descripcion,   pmo.complejidad   , users.nombreyapellido as medico_nombre,
        operacion_cobro_practica.liquidacion_numero, operacion_cobro_practica.estado_liquidacion, estado_facturacion, usuario_realiza_id , usuario_cobro.nombreyapellido as usuario_cobro_nombre,
        usuario_cobro.id as usuario_cobro_id,  paciente.id as paciente_id , paciente.gravado_adherente, obra_social.id as obra_social_id, convenio_os_pmo.id as convenio_os_pmo_id, operacion_cobro.obra_social_id as operacion_cobro_obra_social_id,
        operacion_cobro_practica.usuario_audita_id, operacion_cobro.total_operacion_cobro as operacion_cobro_total_operacion_cobro, operacion_cobro.total_facturado as operacion_cobro_total_facturado, operacion_cobro.total_coseguro as operacion_cobro_total_coseguro,
        operacion_cobro.total_honorarios_medicos as operacion_cobro_total_honorarios_medicos, operacion_cobro.total_otros as operacion_cobro_total_otros, operacion_cobro.fecha_cobro as operacion_cobro_fecha_cobro, operacion_cobro.numero_bono as operacion_cobro_numero_bono, 
        operacion_cobro.liquidacion_numero as operacion_cobro_liquidacion_numero, operacion_cobro.estado as operacion_cobro_estado, operacion_cobro_obra_social.nombre as operacion_cobro_obra_social_nombre,
        CONCAT(operacion_cobro_paciente.apellido,' ' , operacion_cobro_paciente.nombre) as  operacion_cobro_paciente_nombre, operacion_cobro_practica.tiene_observacion, operacion_cobro_practica.motivo_observacion, operacion_cobro.es_anulado as operacion_cobro_es_anulado, 
        operacion_cobro_practica.es_anulado, operacion_cobro_distribucion.obra_social_practica_nombre, operacion_cobro_distribucion.id, operacion_cobro_distribucion.porcentaje distribucion_porcentaje, operacion_cobro_distribucion.valor distribucion_valor, operacion_cobro_distribucion.total as distribucion_total,internacion_tipo
        FROM paciente, convenio_os_pmo,convenio_os_pmo as convenio_os_pmo_distribucion, pmo,users, obra_social,users as usuario_cobro, obra_social as operacion_cobro_obra_social , paciente as operacion_cobro_paciente,operacion_cobro_distribucion, operacion_cobro_practica
        LEFT JOIN operacion_cobro 
        ON  operacion_cobro_practica.operacion_cobro_id = operacion_cobro.id  WHERE operacion_cobro_practica.paciente_id = paciente.id AND operacion_cobro.paciente_id =  operacion_cobro_paciente.id AND  operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id
        AND convenio_os_pmo.obra_social_id = obra_social.id  AND usuario_cobro.id = usuario_realiza_id  AND estado_facturacion = 'P'
        AND operacion_cobro_distribucion.operacion_cobro_id = operacion_cobro.id  AND operacion_cobro_distribucion.convenio_os_pmo_id = convenio_os_pmo_distribucion.id  AND convenio_os_pmo_distribucion.pmo_id = pmo.id  AND estado_liquidacion= :estado_liquidacion AND operacion_cobro_obra_social.id = operacion_cobro.obra_social_id AND obra_social.id = ".$obra_social_id." AND  operacion_cobro.id= ".$id."
    "), array(
        'estado_liquidacion' => $estado_liquidacion   
      ));
     

      return response()->json($horario, 201);
    }



    
    public function getOperacionCobroRegistroDistribucionByIdPrefactura(Request $request)
    {
        $id = $request->input('id');
        $estado_liquidacion =$request->input('estado_liquidacion');
        $obra_social_id =$request->input('obra_social_id');

        $horario = DB::select( DB::raw("  
        SELECT operacion_cobro_practica.id , operacion_cobro.id as operacion_cobro_id, convenio_os_pmo.id as pmo_id,forma_pago, 
        operacion_cobro_practica.valor_facturado, operacion_cobro_practica.valor_facturado as distribucion, operacion_cobro_practica.valor_original, operacion_cobro_practica.categorizacion, operacion_cobro_practica.cantidad,
        operacion_cobro_practica.observacion,operacion_cobro_practica.user_medico_id,operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre, paciente.dni,
        obra_social.nombre as obra_social_nombre, pmo.codigo, pmo.descripcion,   pmo.complejidad   , users.nombreyapellido as medico_nombre,
        operacion_cobro_practica.liquidacion_numero, operacion_cobro_practica.estado_liquidacion, estado_facturacion, usuario_realiza_id , usuario_cobro.nombreyapellido as usuario_cobro_nombre,
        usuario_cobro.id as usuario_cobro_id,  paciente.id as paciente_id , paciente.gravado_adherente, obra_social.id as obra_social_id, convenio_os_pmo.id as convenio_os_pmo_id, operacion_cobro.obra_social_id as operacion_cobro_obra_social_id,
        operacion_cobro_practica.usuario_audita_id, operacion_cobro.total_operacion_cobro as operacion_cobro_total_operacion_cobro, operacion_cobro.total_facturado as operacion_cobro_total_facturado, operacion_cobro.total_coseguro as operacion_cobro_total_coseguro,
        operacion_cobro.total_honorarios_medicos as operacion_cobro_total_honorarios_medicos, operacion_cobro.total_otros as operacion_cobro_total_otros, operacion_cobro.fecha_cobro as operacion_cobro_fecha_cobro, operacion_cobro.numero_bono as operacion_cobro_numero_bono, 
        operacion_cobro.liquidacion_numero as operacion_cobro_liquidacion_numero, operacion_cobro.estado as operacion_cobro_estado, operacion_cobro_obra_social.nombre as operacion_cobro_obra_social_nombre,
        CONCAT(operacion_cobro_paciente.apellido,' ' , operacion_cobro_paciente.nombre) as  operacion_cobro_paciente_nombre, operacion_cobro_practica.tiene_observacion, operacion_cobro_practica.motivo_observacion, operacion_cobro.es_anulado as operacion_cobro_es_anulado, operacion_cobro_practica.es_anulado, 
        operacion_cobro_distribucion.obra_social_practica_nombre, operacion_cobro_distribucion.id, operacion_cobro_distribucion.porcentaje distribucion_porcentaje, operacion_cobro_distribucion.valor distribucion_valor, operacion_cobro_distribucion.total as distribucion_total, internacion_tipo
        FROM paciente, convenio_os_pmo,convenio_os_pmo as convenio_os_pmo_distribucion, pmo,users, obra_social,users as usuario_cobro, obra_social as operacion_cobro_obra_social , paciente as operacion_cobro_paciente,operacion_cobro_distribucion, operacion_cobro_practica
        LEFT JOIN operacion_cobro 
        ON  operacion_cobro_practica.operacion_cobro_id = operacion_cobro.id  WHERE operacion_cobro_practica.paciente_id = paciente.id AND operacion_cobro.paciente_id =  operacion_cobro_paciente.id AND  operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id
        AND convenio_os_pmo.obra_social_id = obra_social.id  AND usuario_cobro.id = usuario_realiza_id  AND estado_facturacion = 'P'
        AND operacion_cobro_distribucion.operacion_cobro_id = operacion_cobro.id  AND operacion_cobro_distribucion.convenio_os_pmo_id = convenio_os_pmo_distribucion.id  AND convenio_os_pmo_distribucion.pmo_id = pmo.id  AND estado_liquidacion= :estado_liquidacion AND operacion_cobro_obra_social.id = operacion_cobro.obra_social_id  AND  operacion_cobro.id= ".$id."
    "), array(
        'estado_liquidacion' => $estado_liquidacion   
      ));
     

      return response()->json($horario, 201);
    }


    public function operacionCobroByCondicion(Request $request)
    {

        $consulta =$request->input('consulta');
        $valor =$request->input('valor');               

        $horario = DB::select( DB::raw("SELECT operacion_cobro_practica.id , operacion_cobro.id as operacion_cobro_id, convenio_os_pmo.id as pmo_id,forma_pago, 
        operacion_cobro_practica.valor_facturado, operacion_cobro_practica.valor_facturado as distribucion, operacion_cobro_practica.valor_original, operacion_cobro_practica.categorizacion, operacion_cobro_practica.cantidad,
        operacion_cobro_practica.observacion,operacion_cobro_practica.user_medico_id,operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre, paciente.dni,
        obra_social.nombre as obra_social_nombre, pmo.codigo, pmo.descripcion,   pmo.complejidad   , users.nombreyapellido as medico_nombre,
        operacion_cobro_practica.liquidacion_numero, operacion_cobro_practica.estado_liquidacion, estado_facturacion, usuario_realiza_id , usuario_cobro.nombreyapellido as usuario_cobro_nombre,
        usuario_cobro.id as usuario_cobro_id,  paciente.id as paciente_id , paciente.gravado_adherente, obra_social.id as obra_social_id, convenio_os_pmo.id as convenio_os_pmo_id,
        operacion_cobro.obra_social_id as operacion_cobro_obra_social_id, operacion_cobro_practica.usuario_audita_id, operacion_cobro.total_operacion_cobro as operacion_cobro_total_operacion_cobro, operacion_cobro.total_facturado as operacion_cobro_total_facturado,
        operacion_cobro.total_coseguro as operacion_cobro_total_coseguro, operacion_cobro.total_honorarios_medicos as operacion_cobro_total_honorarios_medicos, operacion_cobro.total_otros as operacion_cobro_total_otros, operacion_cobro.fecha_cobro as operacion_cobro_fecha_cobro,
        operacion_cobro.numero_bono as operacion_cobro_numero_bono, operacion_cobro.liquidacion_numero as operacion_cobro_liquidacion_numero, operacion_cobro.estado as operacion_cobro_estado, operacion_cobro_obra_social.nombre as operacion_cobro_obra_social_nombre,
        CONCAT(operacion_cobro_paciente.apellido,' ' , operacion_cobro_paciente.nombre) as  operacion_cobro_paciente_nombre,  operacion_cobro_practica.tiene_observacion, operacion_cobro_practica.motivo_observacion, operacion_cobro.es_anulado as operacion_cobro_es_anulado, operacion_cobro_practica.es_anulado, estado_liquidacion, internacion_tipo
        FROM users as usuario_cobro, obra_social as operacion_cobro_obra_social , paciente as operacion_cobro_paciente,operacion_cobro_practica
        LEFT JOIN operacion_cobro 
        ON  operacion_cobro_practica.operacion_cobro_id = operacion_cobro.id , paciente, convenio_os_pmo, pmo,users, obra_social WHERE operacion_cobro_practica.paciente_id = paciente.id AND operacion_cobro.paciente_id =  operacion_cobro_paciente.id AND  operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id AND convenio_os_pmo.obra_social_id = obra_social.id  AND usuario_cobro.id = usuario_realiza_id  AND estado_facturacion = 'P'  AND operacion_cobro_obra_social.id = operacion_cobro.obra_social_id   AND    ".$consulta."  LIKE '".$valor."%'  
    "));


       
      return response()->json($horario, 201);
    }


    public function updateOperacionCobroPractica(Request $request, $id)
    {       
           $update = DB::table('operacion_cobro_practica') 
            ->where('id', $id) ->limit(1) 
            ->update( [ 
             'convenio_os_pmo_id' => $request['convenio_os_pmo_id'],       
             'valor_facturado' => $request['valor_facturado'],
             'forma_pago' => $request['forma_pago'],             
           //  'coeficiente' =>  $request['coeficiente']   ,             
             'paciente_id' =>  $request['paciente_id'] ,
             'user_medico_id' => $request['user_medico_id'],
             'categorizacion' => $request['categorizacion'],                  
             'observacion' => $request['observacion'],    
             'usuario_audita_id' => $request['usuario_audita_id'],     
             'estado_liquidacion' => $request['estado_liquidacion'],    
             'motivo_observacion' => $request['motivo_observacion'],    
             'tiene_observacion' => $request['tiene_observacion'],    
             'liquidacion_numero' => $request['liquidacion_numero'],    
             'es_anulado' => $request['es_anulado'],  
             'internacion_tipo' => $request['internacion_tipo'],     
             'cantidad' => $request['cantidad'],    
             'updated_at' => date("Y-m-d H:i:s")     ]); 
             $operacionCobroPractica = OperacionCobroPractica::findOrFail($id);
            return $this->showOne($operacionCobroPractica);        
    }



    public function updateOperacionCobroPrestacion(Request $request,$id)
    {       
        $i=0;
        while(isset($request[$i])){
          
           $update = DB::table('operacion_cobro_practica') 
            ->where('operacion_cobro_id', $request[$i]["operacion_cobro_id"])
            ->update( [ 
             'internacion_tipo' => $id,       
             'updated_at' => date("Y-m-d H:i:s")     ]); 
           
             
            $i++;
        }
        return response()->json($id, 201);    


             
    }



    public function updateOperacionCobroPracticaEstado(Request $request, $id)
    {       
           $update = DB::table('operacion_cobro_practica') 
            ->where('id', $id) ->limit(1) 
            ->update( [ 
             
             'estado_liquidacion' => $request['estado_liquidacion'],    
             'cantidad' => $request['cantidad'],    
             'updated_at' => date("Y-m-d H:i:s")     ]); 
             $operacionCobroPractica = OperacionCobroPractica::findOrFail($id);
            return $this->showOne($operacionCobroPractica);        
    }




    
    public function updateOperacionCobroPrincipal(Request $request, $id)
    {           
        $tmp_fecha = str_replace('/', '-', $request->input('operacion_cobro_fecha_cobro'));
        $timezone  = -3;
        $fecha_cobro =  date('Y-m-d H:i:s', strtotime($tmp_fecha)+ 3600*($timezone+date("I")));    

        $t = $request->input('operacion_cobro_id');

        $update = DB::table('operacion_cobro') 
        ->where('id', $t) ->limit(1) 
        ->update( [ 
         
            'obra_social_id' => $request->input('operacion_cobro_obra_social_id'),     
            'paciente_id' => $request->input('paciente_id'),                 
            'total_facturado' => $request->input('operacion_cobro_total_facturado'),     
            'total_coseguro' => $request->input('operacion_cobro_total_coseguro'),    
            'total_honorarios_medicos' =>$request->input('operacion_cobro_total_honorarios_medicos'),  
            'total_otros' => $request->input('operacion_cobro_total_otros'),      
            'fecha_cobro' => $fecha_cobro,            
            'numero_bono' => $request->input('operacion_cobro_numero_bono'),   
            'estado' => $request->input('operacion_cobro_estado'),                      
            'liquidacion_numero' =>  $request->input('operacion_cobro_liquidacion_numero') ,    
            'es_anulado' => $request->input('operacion_cobro_es_anulado'),   
         'updated_at' => date("Y-m-d H:i:s")     ]); 

                  
           return response()->json($request, 201);    
    }



    public function updateOperacionCobroPracticaAnular(Request $request, $id)
    {       
        

           $update = DB::table('operacion_cobro_practica') 
            ->where('operacion_cobro_id', $id)
            ->update( [ 
             
             'es_anulado' => $request['es_anulado'],                   
             'updated_at' => date("Y-m-d H:i:s")     ]);              
              
            
            $update = DB::table('operacion_cobro') 
            ->where('id', $id)
            ->update( [ 
             
             'es_anulado' => $request['es_anulado'],                   
             'updated_at' => date("Y-m-d H:i:s")     ]); 
             $operacionCobroPractica = OperacionCobroPractica::findOrFail($id);
            return $this->showOne($operacionCobroPractica);      
    }

    
    
    public function updatePresentacion(Request $request, $id)
    {                   
        $timezone  = -3;
        $tmp_fecha = str_replace('/', '-', $request['fecha_desde']);
        $fecha_desde =  date('Y-m-d', strtotime($tmp_fecha));   
        $tmp_fecha = str_replace('/', '-', $request['fecha_hasta']);
        $fecha_hasta =  date('Y-m-d', strtotime($tmp_fecha));   
        $tmp_numero = str_replace('/', '-', $request['numero']);
        $numero =  date('Y-m-d', strtotime($tmp_numero));   

        $t = $request['id'];

        $update = DB::table('liq_liquidacion') 
        ->where('id', $id) ->limit(1) 
        ->update( [ 
         
            'obra_social_id' => $request['obra_social_id'],     
            'numero' => $numero,                 
            'nivel' => $request['nivel'],     
            'fecha_desde' => $fecha_desde,    
            'fecha_hasta' =>$fecha_hasta,  
            'cant_orden' => $request['cant_orden'],      
            'total' => $request['total'],                  
            'medico_id' => $request['medico_id'],   
            'usuario_audito' =>  $request['usuario_audito'] ,                
         'updated_at' => date("Y-m-d H:i:s")     ]); 

                  
           return response()->json($request, 201);    
    }



    public function auditarOrdenes(Request $request){
        $t =$request;
        $i = 0;
    while(isset($t[$i])){       
        $update = DB::table('operacion_cobro_practica')         
        ->where('id',$t[$i]["id"]) ->limit(1) 
        ->update( [   
         'estado_liquidacion' => 'AUD',  
         'usuario_audita_id' => $t[$i]["usuario_audita_id"],
         'updated_at' => date("Y-m-d H:i:s")     ]);  
            $i++;

        }

        return response()->json($i, "201");

    }






    public function afectarOperacionCobro(Request $request)
    {
        $tmp_fecha = str_replace('/', '-', $request["numero"]);
        $liquidacion =  date('Y-m-d H:i:s', strtotime($tmp_fecha));    
    $liquidacion_numero= DB::table('liq_liquidacion')->insertGetId([
        'obra_social_id' => $request["obra_social_id"],
        'numero' => $liquidacion,
        'nivel' => $request["nivel"],
        'fecha_desde' => $request["fecha_desde"],
        'fecha_hasta' => $request["fecha_hasta"],
        'liquidacion_generada_id' => 0,
        'cant_orden' => $request["cant_orden"],
        'total' => $request["total"],
        'usuario_audito' => $request["usuario_audito"],
        'medico_id'=> $request["medico_id"],
        'estado' => $request["estado"],      
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")    
    ]);    
    
  $i = 0;
    while(isset($request->registros[$i])){       
        $update = DB::table('operacion_cobro_practica')         
        ->where('id',$request->registros[$i]["id"] ) ->limit(1) 
        ->update( [            
         'liquidacion_numero' =>$liquidacion_numero,
         'estado_liquidacion'=>'AFE' ]);  
            $i++;

        }
        //echo  $request->registros[0]["id"];
 return response()->json($liquidacion_numero, 201);        
 
    }


    public function getListadoPreFactura(Request $request){
        /**
         * SELECT liq_liquidacion.id as liq_liquidacion_id, liq_liquidacion.obra_social_id, liq_liquidacion.numero, nivel, fecha_desde, fecha_hasta, liquidacion_generada_id, cant_orden, total, usuario_audito, liq_liquidacion.estado as liq_liquidacion_estado, operacion_cobro_practica.id, operacion_cobro_practica.valor_facturado, operacion_cobro_practica.paciente_id, operacion_cobro_practica.user_medico_id, operacion_cobro_practica.convenio_os_pmo_id, obra_social.nombre as obra_social_nombre, pmo.codigo, pmo.descripcion, pmo.complejidad, operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre FROM operacion_cobro ,liq_liquidacion, operacion_cobro_practica, obra_social,convenio_os_pmo, pmo, users, paciente WHERE liq_liquidacion.id = operacion_cobro_practica.liquidacion_numero AND operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.obra_social_id = obra_social.id and convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id and operacion_cobro.id = operacion_cobro_practica.operacion_cobro_id and operacion_cobro_practica.paciente_id = paciente.id AND liq_liquidacion.id  IN (13,14)
         */
        $in = "";
        $i=0;
        while(isset($request[$i])){
            if($i==0){
            $in = $request[$i]["id"];
            }else{
                $in = $in.",".$request[$i]["id"];
            }
            $i++;
        }
        $horario = DB::select( DB::raw("SELECT liq_liquidacion.id as liq_liquidacion_id, liq_liquidacion.obra_social_id, liq_liquidacion.numero, nivel, fecha_desde, fecha_hasta, liquidacion_generada_id, cant_orden, total, usuario_audito, liq_liquidacion.estado as liq_liquidacion_estado, 
        operacion_cobro_practica.id, operacion_cobro_practica.valor_facturado, operacion_cobro_practica.paciente_id, operacion_cobro_practica.user_medico_id, operacion_cobro_practica.convenio_os_pmo_id, obra_social.nombre as obra_social_nombre,
        pmo.codigo, pmo.descripcion, pmo.complejidad, operacion_cobro.fecha_cobro, CONCAT(paciente.apellido,', ', paciente.nombre) AS paciente_nombre , paciente.dni as paciente_dni, users_practica.nombreyapellido as medico_nombre, medicos.codigo_old as matricula,
        paciente.barra_afiliado  as paciente_barra_afiliado, paciente.numero_afiliado, paciente.gravado_adherente, operacion_cobro.numero_bono, entidad.nombre as entidad_nombre, operacion_cobro_practica.cantidad, entidad.nombre as entidad_nombre, medicos.fecha_matricula , medicos.usuario_id as usuario_id, internacion_tipo
        FROM operacion_cobro ,liq_liquidacion, operacion_cobro_practica, obra_social,convenio_os_pmo, pmo, users, users as users_practica, paciente, entidad , medicos
        WHERE liq_liquidacion.id = operacion_cobro_practica.liquidacion_numero AND operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND
         convenio_os_pmo.obra_social_id = obra_social.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id AND
         operacion_cobro.id = operacion_cobro_practica.operacion_cobro_id and operacion_cobro_practica.paciente_id = paciente.id AND obra_social.entidad_factura_id = entidad.id AND
         operacion_cobro_practica.user_medico_id = users_practica.id AND users.id = medicos.usuario_id AND obra_social.entidad_factura_id = entidad.id AND liq_liquidacion.id  IN (".$in.")  ORDER BY  nivel ASC,matricula ASC, operacion_cobro.fecha_cobro ASC")       ); //nivel-(1,2)  fecha cobro -oc
   $t =$request->desde;
      return response()->json($horario, 201);
    }



//  OBTENGO EL LISTADO DE PREFACTURA DE CIRUGIAS
    
    public function getListadoPreFacturaCirugia(Request $request){
        /**
         * SELECT liq_liquidacion.id as liq_liquidacion_id, liq_liquidacion.obra_social_id, liq_liquidacion.numero, nivel, fecha_desde, fecha_hasta, liquidacion_generada_id, cant_orden, total, usuario_audito, liq_liquidacion.estado as liq_liquidacion_estado, operacion_cobro_practica.id, operacion_cobro_practica.valor_facturado, operacion_cobro_practica.paciente_id, operacion_cobro_practica.user_medico_id, operacion_cobro_practica.convenio_os_pmo_id, obra_social.nombre as obra_social_nombre, pmo.codigo, pmo.descripcion, pmo.complejidad, operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre FROM operacion_cobro ,liq_liquidacion, operacion_cobro_practica, obra_social,convenio_os_pmo, pmo, users, paciente WHERE liq_liquidacion.id = operacion_cobro_practica.liquidacion_numero AND operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.obra_social_id = obra_social.id and convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id and operacion_cobro.id = operacion_cobro_practica.operacion_cobro_id and operacion_cobro_practica.paciente_id = paciente.id AND liq_liquidacion.id  IN (13,14)
         */
        $in = "";
        $i=0;
        while(isset($request[$i])){
            if($i==0){
            $in = $request[$i]["id"];
            }else{
                $in = $in.",".$request[$i]["id"];
            }
            $i++;
        }
     

  $horario = DB::select( DB::raw("  SELECT  operacion_cobro_practica.id as operacion_cobro_practica_id, liq_liquidacion.id as liq_liquidacion_id, liq_liquidacion.obra_social_id, liq_liquidacion.numero, nivel, fecha_desde, fecha_hasta, liquidacion_generada_id, cant_orden, operacion_cobro_distribucion.total, usuario_audito, liq_liquidacion.estado as liq_liquidacion_estado, 
  operacion_cobro_practica.id, operacion_cobro_practica.valor_facturado, operacion_cobro_practica.paciente_id, operacion_cobro_practica.user_medico_id, operacion_cobro_practica.convenio_os_pmo_id, obra_social.nombre as obra_social_nombre,
  pmo.codigo, pmo.descripcion, pmo.complejidad, operacion_cobro.fecha_cobro, CONCAT(paciente.apellido,', ', paciente.nombre) AS paciente_nombre , paciente.dni as paciente_dni, users_practica.nombreyapellido as medico_nombre, medicos.codigo_old as matricula,
  paciente.barra_afiliado  as paciente_barra_afiliado, paciente.numero_afiliado, paciente.gravado_adherente, operacion_cobro.numero_bono, entidad.nombre as entidad_nombre, operacion_cobro_practica.cantidad, entidad.nombre as entidad_nombre, medicos.fecha_matricula, categorizacion, 
  operacion_cobro_distribucion.obra_social_practica_nombre, operacion_cobro_distribucion.porcentaje, operacion_cobro_distribucion.total as operacion_cobro_distribucion_total, operacion_cobro_practica.operacion_cobro_id, internacion_tipo
  FROM operacion_cobro ,liq_liquidacion, obra_social,convenio_os_pmo, pmo, users, users as users_practica, paciente, entidad , medicos,(SELECT*  FROM  operacion_cobro_practica where operacion_cobro_practica.liquidacion_numero IN (".$in.")  )  AS operacion_cobro_practica   LEFT JOIN 
        operacion_cobro_distribucion ON operacion_cobro_practica.operacion_cobro_id = operacion_cobro_distribucion.operacion_cobro_id  WHERE liq_liquidacion.id = operacion_cobro_practica.liquidacion_numero AND operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND
   convenio_os_pmo.obra_social_id = obra_social.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id AND
   operacion_cobro.id = operacion_cobro_practica.operacion_cobro_id and operacion_cobro_practica.paciente_id = paciente.id AND obra_social.entidad_factura_id = entidad.id AND
   operacion_cobro_practica.user_medico_id = users_practica.id AND users.id = medicos.usuario_id AND obra_social.entidad_factura_id = entidad.id   AND   obra_social.id = ".$request[0]["obra_social_id"]."  AND liq_liquidacion.id IN (".$in.")  ORDER BY  operacion_cobro.fecha_cobro ASC, matricula ASC, operacion_cobro_practica.id  ASC")       ); //nivel-(1,2)  fecha cobro -oc
      return response()->json($horario, 201);
    }

    

    
    public function getLiquidacionDetalle(Request $request)
    {
           
    

        $horario = DB::select( DB::raw(" SELECT liq_liquidacion.id, obra_social_id, numero, nivel, fecha_desde, fecha_hasta, liquidacion_generada_id,
        cant_orden, total, usuario_audito, estado, obra_social.nombre as obra_social_nombre, users.nombreyapellido,users_medico.nombreyapellido as medico_nombre,entidad.nombre as entidad_nombre, users_medico.id as medico_id
        FROM liq_liquidacion , obra_social,users , users as users_medico, entidad
        WHERE liq_liquidacion.obra_social_id = obra_social.id AND usuario_audito = users.id AND   obra_social.entidad_factura_id = entidad.id  AND  liq_liquidacion.medico_id = users_medico.id  AND estado !='LIQ' order by    numero DESC
    "));
       
      return response()->json($horario, 201);
    }

// obtengo el listado de ordenes en estado de presentacion que correspondan 
    public function getPresentacionDetalleById(Request $request)
    {
           
    
        $id =$request->input('id');        

        $horario = DB::select( DB::raw("SELECT operacion_cobro_practica.id , operacion_cobro.id as operacion_cobro_id, convenio_os_pmo.id as pmo_id,forma_pago, 
        operacion_cobro_practica.valor_facturado, operacion_cobro_practica.valor_facturado as distribucion, operacion_cobro_practica.valor_original, operacion_cobro_practica.categorizacion, operacion_cobro_practica.cantidad,
        operacion_cobro_practica.observacion,operacion_cobro_practica.user_medico_id,operacion_cobro.fecha_cobro, paciente.apellido, paciente.nombre, paciente.dni,
        obra_social.nombre as obra_social_nombre, pmo.codigo, pmo.descripcion,   pmo.complejidad   , users.nombreyapellido as medico_nombre,
        operacion_cobro_practica.liquidacion_numero, operacion_cobro_practica.estado_liquidacion, estado_facturacion, usuario_realiza_id , usuario_cobro.nombreyapellido as usuario_cobro_nombre,
        usuario_cobro.id as usuario_cobro_id,  paciente.id as paciente_id , paciente.gravado_adherente, obra_social.id as obra_social_id, convenio_os_pmo.id as convenio_os_pmo_id,
        operacion_cobro.obra_social_id as operacion_cobro_obra_social_id, operacion_cobro_practica.usuario_audita_id, operacion_cobro.total_operacion_cobro as operacion_cobro_total_operacion_cobro, operacion_cobro.total_facturado as operacion_cobro_total_facturado,
        operacion_cobro.total_coseguro as operacion_cobro_total_coseguro, operacion_cobro.total_honorarios_medicos as operacion_cobro_total_honorarios_medicos, operacion_cobro.total_otros as operacion_cobro_total_otros, operacion_cobro.fecha_cobro as operacion_cobro_fecha_cobro,
        operacion_cobro.numero_bono as operacion_cobro_numero_bono, operacion_cobro.liquidacion_numero as operacion_cobro_liquidacion_numero, operacion_cobro.estado as operacion_cobro_estado, operacion_cobro_obra_social.nombre as operacion_cobro_obra_social_nombre,
        CONCAT(operacion_cobro_paciente.apellido,' ' , operacion_cobro_paciente.nombre) as  operacion_cobro_paciente_nombre,  operacion_cobro_practica.tiene_observacion, operacion_cobro_practica.motivo_observacion, operacion_cobro.es_anulado as operacion_cobro_es_anulado, operacion_cobro_practica.es_anulado, estado_liquidacion
        FROM users as usuario_cobro, obra_social as operacion_cobro_obra_social , paciente as operacion_cobro_paciente,operacion_cobro_practica
        LEFT JOIN operacion_cobro 
        ON  operacion_cobro_practica.operacion_cobro_id = operacion_cobro.id , paciente, convenio_os_pmo, pmo,users, obra_social WHERE operacion_cobro_practica.paciente_id = paciente.id AND operacion_cobro.paciente_id =  operacion_cobro_paciente.id AND  operacion_cobro_practica.convenio_os_pmo_id = convenio_os_pmo.id AND convenio_os_pmo.pmo_id = pmo.id AND operacion_cobro_practica.user_medico_id = users.id AND convenio_os_pmo.obra_social_id = obra_social.id  AND usuario_cobro.id = usuario_realiza_id  AND estado_facturacion = 'P'  AND operacion_cobro_obra_social.id = operacion_cobro.obra_social_id   AND  operacion_cobro_practica.liquidacion_numero =   ".$id."  
    "));
       
      return response()->json($horario, 201);
    }

    


    public function desafectarPresentacion(Request $request)
    {       
        $liquidacion_nro =$request->input('liquidacion_nro') ;

       $estado = DB::update( DB::raw("
       UPDATE operacion_cobro_practica, operacion_cobro SET operacion_cobro_practica.estado_liquidacion = 'AUD',  operacion_cobro.liquidacion_numero = 0, operacion_cobro_practica.liquidacion_numero = 0 WHERE operacion_cobro_practica.operacion_cobro_id = operacion_cobro.id AND operacion_cobro_practica.estado_liquidacion = 'AFE' AND operacion_cobro.fecha_cobro  AND operacion_cobro_practica.liquidacion_numero= ".$liquidacion_nro."
    "));
       if($estado == 1){}
      DB::table('liq_liquidacion')->where('id', '=', $liquidacion_nro)->delete();
      return response()->json("registro desafectado", 201);      
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\operacionCobro  $operacionCobro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, operacionCobro $operacionCobro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\operacionCobro  $operacionCobro
     * @return \Illuminate\Http\Response
     */
    public function destroy(operacionCobro $operacionCobro)
    {
        //
    }

    public function destroyByPracticaById($id)
    {
        $res=OperacionCobroPractica::where('id',$id)->delete();
        return response()->json($res, 201);
    }
}
