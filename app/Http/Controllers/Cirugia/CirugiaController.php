<?php

namespace App\Http\Controllers\Cirugia;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\models\Lente;

class CirugiaController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $horario = DB::select( DB::raw("SELECT cirugia_ficha.id, paciente_id, cirugia_grupo_medico_id, estudios_id, fecha_cirugia, operacion_cobro_id, ojo, dioptria, observacion, cirugia_ficha.obra_social_id, cirugia_practica, cirugia_ficha_anestesia_id, historia_clinica_id, protocolo_quirurgico_id, estado_cirugia_id, fecha_internacion, fecha_inicio_acto_quirurgico, fecha_fin_acto_quirurgico, fecha_alta, paciente.nombre, paciente.apellido, paciente.dni, obra_social.nombre as obra_social_nombre FROM cirugia_ficha, paciente, obra_social WHERE cirugia_ficha.paciente_id = paciente.id AND  obra_social.id = cirugia_ficha.obra_social_id"));
            return response()->json($horario, 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     
    }

    public function getHistoriaClinicaByPaciente($id){            
        $horario = DB::select( DB::raw(" SELECT fichaoftal01.id, ficha.PACIENTE as dni, MEDICO as matricula_id,  MEDICO, MEDICONOM, FECHA, LEJOS_OD_E, LEJOS_OD_C, LEJOS_OD_2, LEJOS_OD_P, LEJOS_OD_B, LEJOS_OI_E, LEJOS_OI_C, LEJOS_OI_2, LEJOS_OI_P, LEJOS_OI_B, CERCA_OD_E, CERCA_OD_C, CERCA_OD_2, CERCA_OD_P, CERCA_OD_B, CERCA_OI_E, CERCA_OI_C, CERCA_OI_2, CERCA_OI_P, CERCA_OI_B, OBS_LENTES, fichaoftal01.MC, AEA, APP, AF, AV_LEJOS_O, AV_LEJOS_2, AV_LEJOS_3, AV_LEJOS_4, AV_LEJOS_5, AV_LEJOS_6, AV_LEJOS_7, AV_LEJOS_8, RFL_OD_ESF, RFL_OD_CIL, RFL_OD_EJE, RFL_AV_OD, RFL_OI_ESF, RFL_OI_CIL, RFL_OI_EJE, RFL_AV_OI, fichaoftal01.PIO_OD, fichaoftal01.PIO_OI, BIO, COMENTARIO , FO, CVC, OBSERVACIO, SINTOMAS, RFC_OD_ESF, RFC_OD_CIL, RFC_OD_EJE, RFC_AV_OD, RFC_OI_ESF, RFC_OI_CIL, RFC_OI_EJE, RFC_AV_OI, FO, TRATAMIENT, DIAGNOSTIC, REFRACCION_OD, REFRACCION_OI, CICLOPEGIA_OD, CICLOPEGIA_OI, ficha.BIO_OD, ficha.BIO_OI, SINTOMAS_SIGNOS, FONDO_OD, FONDO_OI, DIAGNOSTICO_OD, DIAGNOSTICO_OI, TRATAMIENTO_MEDICO, TRATAMIENTO_QUIRURGICO, estudio_id, estudio_nombre,ESTUDIOSES FROM ficha,fichaoftal01 WHERE ficha.NUMERO = fichaoftal01.NUMERO AND ficha.PACIENTE = ".$id." ORDER BY id DESC")    
      );
 
      return response()->json($horario, 201);
}


    public function setHistoriaClinicaFicha(Request $request){      
        
        
    $id= DB::table('ficha')->insertGetId([
        'paciente_id' => '0',
        'PACIENTE' => $request["PACIENTE"],
        'MEDICO' =>'SIS-NU',
        'MEDICONOM' => $request["MEDICONOM"],
        'OBS_LENTES' => $request["OBS_LENTES"],  
        'CERCA_OD_2' => $request["CERCA_OD_2"],    
        'CERCA_OD_B' => $request["CERCA_OD_B"],    
        'CERCA_OD_C' => $request["CERCA_OD_C"],    
        'CERCA_OD_E' => $request["CERCA_OD_E"],    
        'CERCA_OD_P' => $request["CERCA_OD_P"],    
        'CERCA_OI_2' => $request["CERCA_OI_2"],    
        'CERCA_OI_B' => $request["CERCA_OI_B"],    
        'CERCA_OI_C' => $request["CERCA_OI_C"],    
        'CERCA_OI_E' => $request["CERCA_OI_E"],    
        'CERCA_OI_P' => $request["CERCA_OI_P"],    
        'LEJOS_OD_2' => $request["LEJOS_OD_2"],    
        'LEJOS_OD_B' => $request["LEJOS_OD_B"],    
        'LEJOS_OD_C' => $request["LEJOS_OD_C"],    
        'LEJOS_OD_E' => $request["LEJOS_OD_E"],    
        'LEJOS_OD_P' => $request["LEJOS_OD_P"],    
        'LEJOS_OI_2' => $request["LEJOS_OI_2"],    
        'LEJOS_OI_B' => $request["LEJOS_OI_B"],    
        'LEJOS_OI_C' => $request["LEJOS_OI_C"],    
        'LEJOS_OI_E' => $request["LEJOS_OI_E"],    
        'LEJOS_OI_P' => $request["LEJOS_OI_P"],    
        'SINTOMAS' => $request["SINTOMAS"],   
        'TRATAMIENT' => $request["TRATAMIENT"],   
        'DIAGNOSTIC' => $request["DIAGNOSTIC"],  
        'REFRACCION_OD' => $request["REFRACCION_OD"],  
        'REFRACCION_OI' => $request["REFRACCION_OI"],  
        'CICLOPEGIA_OD' => $request["CICLOPEGIA_OD"],  
        'CICLOPEGIA_OI' => $request["CICLOPEGIA_OI"],  
        'PIO_OD' => $request["PIO_OD"],  
        'PIO_OI' => $request["PIO_OI"],  
        'BIO_OD' => $request["BIO_OD"],  
        'BIO_OI' => $request["BIO_OI"],  
        'SINTOMAS_SIGNOS' => $request["SINTOMAS_SIGNOS"],  
        'FONDO_OD' => $request["FONDO_OD"],  
        'FONDO_OI' => $request["FONDO_OI"],  
        'DIAGNOSTICO_OD' => $request["DIAGNOSTICO_OD"],  
        'DIAGNOSTICO_OI' => $request["DIAGNOSTICO_OI"],  
        'TRATAMIENTO_MEDICO' => $request["TRATAMIENTO_MEDICO"],  
        'TRATAMIENTO_QUIRURGICO' => $request["TRATAMIENTO_QUIRURGICO"],   
        'ESTUDIOSES' => $request["ESTUDIOSES"],         
        'FECHA' => date("Y-m-d H:i:s")        
                
        
    ]);    

       
    $liquidacion_numero= DB::table('fichaoftal01')->insertGetId([
        'NUMERO' => $id,
        'MC' => $request["MC"],
        'AEA' => $request["AEA"],
        'APP' => $request["APP"],
        'AF' => $request["AF"],
        'BIO' => $request["BIO"],
        'COMENTARIO' => $request["COMENTARIO"],        
        'PIO_OD' => $request["PIO_OD"],
        'PIO_OI' => $request["PIO_OI"],                
        'AV_LEJOS_O' => $request["AV_LEJOS_O"],    
        'AV_LEJOS_2' => $request["AV_LEJOS_2"],    
        'AV_LEJOS_3' => $request["AV_LEJOS_3"],    
        'AV_LEJOS_4' => $request["AV_LEJOS_4"],    
        'AV_LEJOS_5' => $request["AV_LEJOS_5"],    
        'AV_LEJOS_6' => $request["AV_LEJOS_6"],    
        'AV_LEJOS_7' => $request["AV_LEJOS_7"],    
        'AV_LEJOS_8' => $request["AV_LEJOS_8"],    
        'RFL_OD_ESF' => $request["RFL_OD_ESF"],    
        'RFL_OD_CIL' => $request["RFL_OD_CIL"],    
        'RFL_OD_EJE' => $request["RFL_OD_EJE"],    
        'RFL_AV_OD' => $request["RFL_AV_OD"],    
        'RFL_OI_ESF' => $request["RFL_OI_ESF"],    
        'RFL_OI_CIL' => $request["RFL_OI_CIL"],    
        'RFL_OI_EJE' => $request["RFL_OI_EJE"],    
        'RFL_AV_OI' => $request["RFL_AV_OI"],    
        'RFC_OD_ESF' => $request["RFC_OD_ESF"],    
        'RFC_OD_CIL' => $request["RFC_OD_CIL"],    
        'RFC_OD_EJE' => $request["RFC_OD_EJE"],    
        'RFC_AV_OD' => $request["RFC_AV_OD"],    
        'RFC_OI_ESF' => $request["RFC_OI_ESF"],    
        'RFC_OI_CIL' => $request["RFC_OI_CIL"],    
        'RFC_OI_EJE' => $request["RFC_OI_EJE"],    
        'RFC_AV_OI' => $request["RFC_AV_OI"],    
        'FO' => $request["FO"],    
        
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")    
    ]);    
    return response()->json($request, 201);
    }


     public function updHistoriaClinicaById(Request $request,$id){
     
         $tmp_fecha = str_replace('/', '-', $request['fecha_vencimiento']);
         $fecha_vencimiento =  date('Y-m-d ', strtotime($tmp_fecha)); 
     
         $update = DB::table('lente') 
         ->where('id', $id) ->limit(1) 
         ->update( [ 
          'tipo_lente_id' => $request['tipo_lente_id'],       
          'dioptria' => $request['dioptria'],
          'lote' =>  $request['lote']   ,             
          'fecha_vencimiento' =>  $fecha_vencimiento ,
          'ubicacion' => $request['ubicacion'],
          'estado' => $request['estado']['name'],                                          
          'updated_at' => date("Y-m-d H:i:s")     ]); 
          $Lente = Lente::findOrFail($id);
         return $this->showOne($Lente);           
         
     }


     public function crearEstudio(Request $request)
     {
          
        $tmp_fecha = str_replace('/', '-', $request['fecha_estudio']);
        $fecha_estudio =  date('Y-m-d ', strtotime($tmp_fecha)); 
         $id= DB::table('estudio')->insertGetId([
             'convenio_os_pmo_id' => $request->paciente_id,
             'paciente_id' => $request->medico_deriva_id,
             'medico_id' => $request->total_operacion_cobro,           
             'fecha_estudio' => $fecha_estudio,     
             'usuario_realiza_id' => $request->usuario_id,     
             'created_at' => date("Y-m-d H:i:s"),
             'updated_at' => date("Y-m-d H:i:s")    
         ]);

         $id= DB::table('ficha')->insertGetId([
            'paciente_id' => '0',
            'PACIENTE' => $request["PACIENTE"],
            'MEDICO' =>'SIS-NU',
            'MEDICONOM' => $request["MEDICONOM"],
            'OBS_LENTES' => $request["OBS_LENTES"],  
            'CERCA_OD_2' => $request["CERCA_OD_2"],    
            'CERCA_OD_B' => $request["CERCA_OD_B"],    
            'CERCA_OD_C' => $request["CERCA_OD_C"],    
            'CERCA_OD_E' => $request["CERCA_OD_E"],    
            'CERCA_OD_P' => $request["CERCA_OD_P"],    
            'CERCA_OI_2' => $request["CERCA_OI_2"],    
            'CERCA_OI_B' => $request["CERCA_OI_B"],    
            'CERCA_OI_C' => $request["CERCA_OI_C"],    
            'CERCA_OI_E' => $request["CERCA_OI_E"],    
            'CERCA_OI_P' => $request["CERCA_OI_P"],    
            'LEJOS_OD_2' => $request["LEJOS_OD_2"],    
            'LEJOS_OD_B' => $request["LEJOS_OD_B"],    
            'LEJOS_OD_C' => $request["LEJOS_OD_C"],    
            'LEJOS_OD_E' => $request["LEJOS_OD_E"],    
            'LEJOS_OD_P' => $request["LEJOS_OD_P"],    
            'LEJOS_OI_2' => $request["LEJOS_OI_2"],    
            'LEJOS_OI_B' => $request["LEJOS_OI_B"],    
            'LEJOS_OI_C' => $request["LEJOS_OI_C"],    
            'LEJOS_OI_E' => $request["LEJOS_OI_E"],    
            'LEJOS_OI_P' => $request["LEJOS_OI_P"],    
            'SINTOMAS' => $request["SINTOMAS"],   
            'TRATAMIENT' => $request["TRATAMIENT"],   
            'DIAGNOSTIC' => $request["DIAGNOSTIC"],  
            'REFRACCION_OD' => $request["REFRACCION_OD"],  
            'REFRACCION_OI' => $request["REFRACCION_OI"],  
            'CICLOPEGIA_OD' => $request["CICLOPEGIA_OD"],  
            'CICLOPEGIA_OI' => $request["CICLOPEGIA_OI"],  
            'PIO_OD' => $request["PIO_OD"],  
            'PIO_OI' => $request["PIO_OI"],  
            'BIO_OD' => $request["BIO_OD"],  
            'BIO_OI' => $request["BIO_OI"],  
            'SINTOMAS_SIGNOS' => $request->SINTOMAS_SIGNOS,  
            'FONDO_OD' => $request["FONDO_OD"],  
            'FONDO_OI' => $request["FONDO_OI"],  
            'DIAGNOSTICO_OD' => $request["DIAGNOSTICO_OD"],  
            'DIAGNOSTICO_OI' => $request["DIAGNOSTICO_OI"],  
            'TRATAMIENTO_MEDICO' => $request["TRATAMIENTO_MEDICO"],  
            'TRATAMIENTO_QUIRURGICO' => $request["TRATAMIENTO_QUIRURGICO"],          
            'estudio_id' => $id,   
            'FECHA' => date("Y-m-d H:i:s")        
            
            
        ]);    

        return response()->json($request, 201);
     }
 
   


    
    public function getFichaQuirurgica($estado){


       $horario = DB::select( DB::raw("SELECT CONCAT(medico_deriva.apellido ,' ', medico_deriva.nombre) as medico_deriva, cirugia_ficha.operacion_cobro_id ,cirugia_ficha.cirugia_medico_grupo_id, cirugia_ficha.id, paciente_id, medico_deriva_id, fecha_derivacion, estado_cirugia_id, paciente.apellido as paciente_apellido, paciente.nombre as paciente_nombre, paciente.dni as paciente_dni, paciente.fecha_nacimiento paciente_fecha_nacimiento, cirugia_estado.estado,  obra_social.nombre as obra_social_nombre, obra_social.id as obra_social_id, ojo, cirugia_practica
       FROM cirugia_ficha,paciente ,cirugia_estado, medicos as medico_deriva, users, obra_social
       WHERE cirugia_ficha.paciente_id = paciente.id AND users.id = cirugia_ficha.medico_deriva_id AND users.id = medico_deriva.usuario_id AND cirugia_ficha.estado_cirugIa_id =  cirugia_estado.id AND paciente.obra_social_id = obra_social.id AND  estado !='REALIZADO' ORDER BY fecha_derivacion DESC
        "), array(
        'estado' =>$estado
      ));
     return response()->json($horario, 201);
    }

    public function getFichaQuirurgicaGrupoMedico(Request $request, $id){
        $horario = DB::select( DB::raw("SELECT cirugia_medico_grupo.id as cirugia_grupo_medico_id, medico_opera_id, medico_deriva_id, medico_ayuda_id, medico_factura_id, medico_anestesista_id, user_medico_opera.nombreyapellido AS medico_opera_nombre, users_medico_deriva.nombreyapellido as medico_deriva_nombre, users_medico_ayuda.nombreyapellido as medico_ayuda_nombre, users_medico_factura.nombreyapellido as medico_factura_nombre, users_medico_anestesista.nombreyapellido as medico_anestesista_nombre  FROM cirugia_medico_grupo , users as user_medico_opera, users as users_medico_deriva, users as users_medico_ayuda, users as users_medico_factura, users as users_medico_anestesista WHERE cirugia_medico_grupo.medico_opera_id = user_medico_opera.id AND cirugia_medico_grupo.medico_deriva_id = users_medico_deriva.id AND cirugia_medico_grupo.medico_ayuda_id = users_medico_ayuda.id AND cirugia_medico_grupo.medico_factura_id = users_medico_factura.id AND cirugia_medico_grupo.medico_anestesista_id = users_medico_anestesista.id AND cirugia_medico_grupo.id =  :id
        "), array(
        'id' =>$id
        
      ));

      return response()->json($horario, 201);
    }


    public function getCirugiaEstado(Request $request){
        $estado = DB::table( 'cirugia_estado')
        ->select(
            'id',
            'estado'       
           )                                
            ->get();
            return response()->json($estado, 201);    
    }
    
    public function getFichaQuirurgicaEstudios(Request $request){}
    public function getFichaQuirurgicaAnestesia(Request $request){
        $horario = DB::select( DB::raw("SELECT cirugia_medico_grupo.id as cirugia_medico_grupo_id, medico_opera_id, medico_deriva_id, medico_ayuda_id, medico_factura_id, medico_anestesista_id, user_medico_opera.nombreyapellido AS user_medico_opera_nombre, users_medico_deriva.nombreyapellido as users_medico_deriva_nombre, users_medico_ayuda.nombreyapellido as users_medico_ayuda_nombre, users_medico_factura.nombreyapellido as users_medico_factura_nombre, users_medico_anestesista.nombreyapellido as users_medico_anestesista_nombre  FROM cirugia_medico_grupo , users as user_medico_opera, users as users_medico_deriva, users as users_medico_ayuda, users as users_medico_factura, users as users_medico_anestesista WHERE cirugia_medico_grupo.medico_opera_id = user_medico_opera.id AND cirugia_medico_grupo.medico_deriva_id = users_medico_deriva.id AND cirugia_medico_grupo.medico_ayuda_id = users_medico_ayuda.id AND cirugia_medico_grupo.medico_factura_id = users_medico_factura.id AND cirugia_medico_grupo.medico_anestesista_id = users_medico_anestesista.id AND cirugia_medico_grupo.id =  :id
        "), array(
        'id' =>$id
        
      ));
 
    }        

    
    public function getFichaQuirurgicaPractica(Request $request){}
    public function getFichaQuirurgicaRendicion(Request $request){}

    public function getFichaQuirurgicaLente($id){    
            $horario = DB::select( DB::raw("SELECT cirugia_id, lente_id, tipo_lente_id,dioptria,lote, fecha_vencimiento,tipo,proveedor,ubicacion,estado, es_baja, usuario_modifico FROM cirugia_lente, lente, lente_tipo WHERE cirugia_lente.lente_id = lente.id AND lente_tipo.id = lente.tipo_lente_id AND  estado!='USADO' AND cirugia_lente.cirugia_id = :id "), 
            array(
            'id' =>$id
          ));
     
          return response()->json($horario, 201);
    }


    public function getFichaQuirurgicaListadoQuirofano($estado){


        $horario = DB::select( DB::raw("SELECT CONCAT(medico_deriva.apellido ,' ', medico_deriva.nombre) as medico_deriva,cirugia_ficha.cirugia_medico_grupo_id, cirugia_ficha.id, paciente_id, medico_deriva_id, fecha_derivacion, estado_cirugia_id, paciente.apellido as paciente_apellido, paciente.nombre as paciente_nombre, paciente.dni as paciente_dni, paciente.fecha_nacimiento paciente_fecha_nacimiento, cirugia_estado.estado,  obra_social.nombre as obra_social_nombre, obra_social.id as obra_social_id, ojo, cirugia_practica, cirugia_listado_quirofano.orden,
        cirugia_listado_quirofano.usuario_crea_id , cirugia_listado_quirofano.usuario_modifica_id, cirugia_listado_quirofano.fecha_ultima_modificacion, cirugia_listado_quirofano.estado as cirugia_listado_quirofano_estado, cirugia_listado_quirofano_usuario_crea.nombreyapellido as usuario_crea_nombre, cirugia_listado_quirofano_usuario_modifico.nombreyapellido as usuario_modifica_nombre 
                FROM cirugia_ficha,paciente ,cirugia_estado, medicos as medico_deriva, users, obra_social, cirugia_listado_quirofano, users as cirugia_listado_quirofano_usuario_modifico,  users as cirugia_listado_quirofano_usuario_crea 
                WHERE cirugia_ficha.paciente_id = paciente.id AND users.id = cirugia_ficha.medico_deriva_id AND users.id = medico_deriva.usuario_id AND cirugia_ficha.estado_cirugIa_id =  cirugia_estado.id AND paciente.obra_social_id = obra_social.id AND cirugia_listado_quirofano.cirugia_ficha_id = cirugia_ficha.id AND  cirugia_estado.estado = :estado ORDER BY fecha_derivacion DESC
         "), array(
         'estado' =>$estado
       ));
      return response()->json($horario, 201);
     }

     public function crearRegistroListadoQuirofano(Request $request){

        $tmp_fecha = str_replace('/', '-', $request->input('fecha_ultima_modificacion'));
        $timezone  = -3;
        $fecha_ultima_modificacion =  date('Y-m-d H:i:s', strtotime($tmp_fecha)+ 3600*($timezone+date("I")));           

        $id= DB::table('cirugia_listado_quirofano')->insertGetId([
            'cirugia_ficha_id' => $request->cirugia_ficha_id,
            'orden' => $request->orden,
            'usuario_crea_id' => $request->usuario_crea_id,           
            'usuario_modifica_id' => $request->usuario_modifica_id,                                   
            'fecha_ultima_modificacion' => $fecha_ultima_modificacion,              
            'estado' => $request->estado,              
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);
        $item = OperacionCobroPractica::findOrFail($id);
        return $this->showOne($item); 
    }
 

    public function actualizarRegistroListadoQuirofano(Request $request,$id){

        $tmp_fecha = str_replace('/', '-', $request['fecha_ultima_modificacion'] );
        $timezone  = -3;
        $fecha_ultima_modificacion =  date('Y-m-d H:i:s', strtotime($tmp_fecha)+ 3600*($timezone+date("I")));        

        $update = DB::table('cirugia_listado_quirofano') 
        ->where('id', $id) ->limit(1) 
        ->update( [ 
         'orden' => $request['orden'],
         'usuario_modifica_id' => $request['usuario_modifica_id'],   
         'fecha_ultima_modificacion' => $fecha_ultima_modificacion,   
         'estado' => $request['estado'],   
                      
         'updated_at' => date("Y-m-d H:i:s")     ]);         
         return response()->json($update, 201);
    }

    public function crearRegistroGrupoMedico(Request $request){
        $id= DB::table('cirugia_medico_grupo')->insertGetId([
            'medico_opera_id' => $request->medico_opera_id,
            'medico_deriva_id' => $request->medico_deriva_id,
            'medico_ayuda_id' => $request->medico_ayuda_id,           
            'medico_factura_id' => $request->medico_factura_id,       
            'medico_anestesista_id' => $request->medico_anestesista_id,                   
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);

        $update = DB::table('cirugia_ficha') 
        ->where('id', $request->cirugia_ficha_id) ->limit(1) 
        ->update( [ 
         'cirugia_medico_grupo_id' => $id,                                                         
         'updated_at' => date("Y-m-d H:i:s")     ]);         
         return response()->json($id, 201);
    }

    public function crearRegistroEstudios(Request $request){

        $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
        $fecha_estudio =  date('Y-m-d', strtotime($tmp_fecha));  

        $id= DB::table('estudio')->insertGetId([
            'convenio_os_pmo_id' => $request->medico_opera_id,
            'cirugia_grupo_medico_factura_id' => $request->medico_deriva_id,
            'practica_id' => $request->medico_ayuda_id,           
            'fecha_estudio' => $request->fecha_estudio,                                   
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);
        $item = OperacionCobroPractica::findOrFail($id);
        return $this->showOne($item); 
    }

    public function crearRegistroAnestesia(Request $request){}            
    public function crearRegistroRendicion(Request $request){}

    public function crearRegistroLente(Request $request){
     
/*********************SI LA CIRUGIA NO TIENE LENTE EL VALOR DE  $request->cirugia_lente_id ES  0 Y DEBE SALTEAR EL CONTRO DE DUPLICIDIDAD */
 $t_cirugia_lente_id = $request->cirugia_lente_id;
 $sin_lente = false;
     if($t_cirugia_lente_id == "0"){
        $sin_lente = true;
         $t_cirugia_lente_id = "451";
         $lente = 1;
     }else{
        $lente = DB::select( DB::raw("SELECT cirugia_id, lente_id, tipo_lente_id,dioptria,lote, fecha_vencimiento,tipo,proveedor,ubicacion,estado, es_baja, usuario_modifico FROM cirugia_lente, lente, lente_tipo WHERE cirugia_lente.lente_id = lente.id AND lente_tipo.id = lente.tipo_lente_id AND  estado!='USADO' AND lente_id IN(:id,0) "), 
        array(
        'id' =>$request->cirugia_lente_id
      ));
    }
      if(($lente) &&($sin_lente == false)){
        return response()->json("LENTE EXISTENTE", 201);
      }else{
        $cirugia_lente_id= DB::table('cirugia_lente')->insertGetId([
            'cirugia_id' => $request->id,
            'lente_id' => $t_cirugia_lente_id ,                                             
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);
        $item = Lente::findOrFail($cirugia_lente_id);

         // actualizo el valor creado en la ficha
        $update = DB::table('cirugia_ficha') 
        ->where('id', $request->id ) ->limit(1) 
        ->update( [ 
         'cirugia_lente_id' => $cirugia_lente_id,               
         'updated_at' => date("Y-m-d H:i:s")     ]);  
            //cambio el estado del lente
    if($t_cirugia_lente_id != "451"){
        $update = DB::table('lente') 
        ->where('id', $t_cirugia_lente_id ) ->limit(1) 
        ->update( [ 
         'estado' => "ASIGNADO A CIRUGIA",               
         'updated_at' => date("Y-m-d H:i:s")     ]);   
        }  
    }
         return response()->json($item, 201); 
    }
// INCOMPLETO DEBE PASAR EL NUMERO DE CIRUGIA PARA EVITAR BORRAR LOS LENTES VACIOS
    public function destroyRegistroLenteFichaQuirugica(Request $request){
      //  if($request->lente_id != "451"){
        DB::table('cirugia_lente')        
        ->where('lente_id', '=', $request->lente_id)
        ->where('cirugia_id', '=', $request->cirugia_id)
        ->delete();
       // }
        return response()->json($request->cirugia_id, 201);
    }

    public function actualizarRegistroCirugiaGrupoMedico(Request $request,$id){
        $update = DB::table('cirugia_medico_grupo') 
        ->where('id', $request['cirugia_grupo_medico_id']) ->limit(1) 
        ->update( [ 
         'medico_opera_id' => $request['medico_opera_id'],
         'medico_deriva_id' => $request['medico_deriva_id'],   
         'medico_ayuda_id' => $request['medico_ayuda_id'],   
         'medico_factura_id' => $request['medico_factura_id'],   
         'medico_anestesista_id' => $request['medico_anestesista_id'],               
         'updated_at' => date("Y-m-d H:i:s")     ]);         
         return response()->json($update, 201);
    }

    
    public function actualizarRegistroCirugiaEstudios(Request $request){
           /**  SIN USO POR AHORA */
    }

    public function actualizarRegistroCirugiaAnestesia(Request $request){
           /**  SIN USO POR AHORA */
    }        

    public function actualizarRegistroCirugiaPractica(Request $request,$id){

           $update = DB::table('cirugia_ficha') 
        ->where('id', $id) ->limit(1) 
        ->update( [ 
         'operacion_cobro_id' => $request['operacion_cobro_id'],                
         'usuario_modifico_id' => $request['usuario_modifico_id'],                
         'updated_at' => date("Y-m-d H:i:s")     ]);          
         return response()->json($update, 201);

    }

    public function actualizarRegistroCirugiaEstado(Request $request,$id){

        $tmp_fecha = str_replace('/', '-', $request->input('fecha_internacion'));
        $fecha_internacion =  date('Y-m-d', strtotime($tmp_fecha));  
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_inicio_acto_quirurgico'));
        $fecha_inicio_acto_quirurgico =  date('Y-m-d', strtotime($tmp_fecha));  
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_fin_acto_quirurgico'));
        $fecha_fin_acto_quirurgico =  date('Y-m-d', strtotime($tmp_fecha));  
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_alta'));
        $fecha_alta =  date('Y-m-d', strtotime($tmp_fecha));  


        $update = DB::table('cirugia_ficha') 
     ->where('id', $id) ->limit(1) 
     ->update( [ 
      'estado_cirugia_id' => $request['estado_cirugia_id'],                
      'observacion_cirugia' => $request['observacion_cirugia'],                
      'fecha_internacion' => $fecha_internacion,  
      'fecha_inicio_acto_quirurgico' => $fecha_inicio_acto_quirurgico,  
      'fecha_fin_acto_quirurgico' => $fecha_fin_acto_quirurgico,  
      'fecha_alta' => $fecha_alta,  
      'usuario_modifico_id' => $request['usuario_modifico_id'], 
      'updated_at' => date("Y-m-d H:i:s")     ]);          
      return response()->json($update, 201);

 }



    public function actualizarRegistroCirugiaRendicion(Request $request){}

    public function crearFichaQuirurgicaLente(Request $request){}
        
      
   

    public function crearRegistroCirugia(Request $request )
    {
        
        $id= DB::table('cirugia_ficha')->insertGetId([
            'paciente_id' => $request->paciente_id,
            'medico_deriva_id' => $request->medico_deriva_id,
            'observacion' => $request->observacion,     
            'ojo' => $request->ojo,   
            'fecha_derivacion' => $request->fecha_derivacion,           
            'cirugia_medico_grupo_id' => $request->cirugia_medico_grupo_id,        
            'obra_social_id' => $request->obra_social_id,        
            'cirugia_practica' => $request->cirugia_practica,        
            'cirugia_lente_id' => $request->cirugia_lente_id,        
            'estado_cirugia_id' => $request->estado_cirugia_id,  
            'usuario_audito' => $request->medico_deriva_id,   
            'usuario_modifico_id' => $request->medico_deriva_id,           
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);

        $id_atendido= DB::table('cirugia_asesoramiento_derivados')->insertGetId([
            'paciente_id' => $request->paciente_id,
            'cirugia_ficha_id' => $id,
            'fecha_derivacion' => $request->fecha_derivacion,
            'es_atendido' => "NO",  
            'usuario_atendio_id' => $request->medico_deriva_id,               
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);

        return response()->json($id, 201);
    }

    

  



    public function getFichAsesoramientoDerivados(Request $request){

        $fecha = $request->input('fecha_derivacion');
        $horario = DB::select( DB::raw("SELECT cirugia_asesoramiento_derivados.id, cirugia_asesoramiento_derivados.paciente_id, cirugia_asesoramiento_derivados.fecha_derivacion, es_atendido, usuario_atendio_id, fecha_atencion,paciente.apellido as paciente_apellido, paciente.nombre as paciente_nombre , paciente.dni as paciente_dni, users.nombreyapellido as usuario_atendio,  users_medico.nombreyapellido as medico_deriva, cirugia_ficha.cirugia_practica, cirugia_ficha.ojo
           FROM cirugia_asesoramiento_derivados,cirugia_ficha, paciente, users , users as users_medico WHERE cirugia_asesoramiento_derivados.cirugia_ficha_id = cirugia_ficha.id AND cirugia_asesoramiento_derivados.paciente_id = paciente.id AND cirugia_ficha.medico_deriva_id = users_medico.id  AND  cirugia_asesoramiento_derivados.usuario_atendio_id = users.id  AND cirugia_asesoramiento_derivados.fecha_derivacion BETWEEN '".$fecha."  00:00:00' AND '".$fecha." 23:59:59' 
         "));
      return response()->json($horario, 201);
     }


    
     public function actualizarFichAsesoramientoDerivados(Request $request,$id){

        $timezone  = -3;
        //$fecha_ultima_modificacion =  date('Y-m-d H:i:s', date("Y-m-d H:i:s") + 3600*($timezone+date("I")));   

        $update = DB::table('cirugia_asesoramiento_derivados') 
        ->where('id', $request['id']) ->limit(1) 
        ->update( [ 
         'usuario_atendio_id' => $request['usuario_atendio_id'],                
         'es_atendido' =>  "SI"   ,             
         'fecha_atencion' =>  date("Y-m-d H:i:s")  ,      
         'updated_at' => date("Y-m-d H:i:s")     ]); 
         $Lente = Lente::findOrFail($id);
        return $this->showOne($Lente);           
   
    }


    public function crearLente(Request $request){

        $tmp_fecha = str_replace('/', '-', $request->fecha_vencimiento);
        $fecha_vencimiento =  date('Y-m-d ', strtotime($tmp_fecha));  

        $id= DB::table('lente')->insertGetId([
            'tipo_lente_id' => $request->tipo_lente_id,
            'dioptria' => $request->dioptria,                                            
            'lote' => $request->lote,    
            'fecha_vencimiento' => $fecha_vencimiento,    
            'ubicacion' => $request->ubicacion,    
            'estado' => $request->estado["name"],   
            'es_baja' => 'NO',   
            'usuario_modifico' => $request->usuario_modifico,   
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);
        $item = Lente::findOrFail($id);
        return $this->showOne($item); 
    }


    public function getLentes(Request $request){

        $horario = DB::select( DB::raw("SELECT lente.id, tipo_lente_id, dioptria, lote, fecha_vencimiento, ubicacion, estado, lente_tipo.tipo, lente_tipo.proveedor, es_baja, usuario_modifico FROM lente, lente_tipo WHERE  lente.tipo_lente_id = lente_tipo.id AND  estado !='USADO'
        "), array(
        
      ));
     return response()->json($horario, 201);
        
    }

    
    public function actualizarLente(Request $request,$id){

        $tmp_fecha = str_replace('/', '-', $request['fecha_vencimiento']);
        $fecha_vencimiento =  date('Y-m-d ', strtotime($tmp_fecha)); 

        $update = DB::table('lente') 
        ->where('id', $id) ->limit(1) 
        ->update( [ 
         'tipo_lente_id' => $request['tipo_lente_id'],       
         'dioptria' => $request['dioptria'],
         'lote' =>  $request['lote']   ,             
         'fecha_vencimiento' =>  $fecha_vencimiento ,
         'ubicacion' => $request['ubicacion'],
         'estado' => $request['estado']['name'],  
         'es_baja' =>  $request['es_baja'],   
         'usuario_modifico' =>  $request['usuario_modifico'],       
         'updated_at' => date("Y-m-d H:i:s")     ]); 
         $Lente = Lente::findOrFail($id);
        return $this->showOne($Lente);           
   
    }


    


    public function getLenteTipo(Request $request){

        $horario = DB::select( DB::raw("SELECT id, tipo, proveedor FROM  lente_tipo 
        "), array(
        
      ));
     return response()->json($horario, 201);
        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function actualizarRegistroHistoriaClinica(Request $request)
    {
        $inicio = $request->inicio;
        $fin=  $request->fin;
        $horario = DB::select( DB::raw("SELECT id, dni  FROM  paciente WHERE id BETWEEN ".$inicio." AND ".$fin."
        "));
         //  echo $horario[0]->id;
        $i = 0;
        foreach ($horario as $res) {
          //  echo $horario[$i]->id;

            $update = DB::table('ficha') 
            ->where('PACIENTE', $horario[$i]->dni) ->limit(1) 
            ->update( [ 
             'paciente_id' => $horario[$i]->id
             ]); 

            $i++;
        }
    return response()->json($horario, 201);
    }


/*********** obtengo el listado de cirugias del dia */
    public function getListadoQuirofano(Request $request){

        $estado = $request->input('estado');
        $tmp_fecha = str_replace('/', '-', $request['fecha']);
        $fecha =  date('Y-m-d', strtotime($tmp_fecha)); 
        
        $horario = DB::select( DB::raw("SELECT cirugia_listado_quirofano.id  ,CONCAT(medico_deriva.apellido ,' ', medico_deriva.nombre) as medico_deriva,cirugia_ficha.cirugia_medico_grupo_id, cirugia_ficha.id as cirugia_ficha_id, cirugia_ficha.paciente_id, cirugia_ficha.medico_deriva_id, fecha_derivacion, estado_cirugia_id, paciente.apellido as paciente_apellido, paciente.nombre as paciente_nombre,
        paciente.dni as paciente_dni,paciente.numero_afiliado as paciente_numero_afiliado, paciente.fecha_nacimiento paciente_fecha_nacimiento, cirugia_estado.estado,  obra_social.nombre as obra_social_nombre, coseguro.nombre as coseguro_nombre, coseguro.id as coseguro_id, obra_social.id as obra_social_id, ojo, cirugia_practica, usuario_modifico.nombreyapellido as cirugia_ficha_usuario_modifico, cirugia_ficha.usuario_modifico_id as cirugia_ficha_usuario_modifico_id , usuario_audito.nombreyapellido as cirugia_ficha_usuario_audito, usuario_audito.id as cirugia_ficha_usuario_audito_id ,  cirugia_ficha.usuario_audito,
        cirugia_listado_quirofano.orden, cirugia_listado_quirofano.fecha_hora ,cirugia_listado_quirofano.usuario_crea_id, usuario_listado_crea.nombreyapellido as usuario_listado_creo_nombre, cirugia_listado_quirofano.usuario_modifica_id ,usuario_listado_modifico.nombreyapellido as usuario_listado_modifico_nombre, lente.dioptria, lente.fecha_vencimiento as lente_vencimiento, lente.lote, lente.estado as lente_estado, lente_tipo.tipo as lente_tipo, usuario_medico_opera.nombreyapellido as usuario_medico_opera_nombre, usuario_medico_ayuda.nombreyapellido as usuario_medico_ayuda_nombre, usuario_medico_anestesista.nombreyapellido as usuario_medico_anestesista_nombre, cirugia_listado_quirofano.observacion as quirofano_observacion, cirugia_listado_quirofano.tiene_observacion
       FROM cirugia_ficha,paciente ,cirugia_estado, medicos as medico_deriva, users, obra_social, obra_social as coseguro,  cirugia_lente, lente, lente_tipo, users as usuario_modifico, users as usuario_audito, cirugia_listado_quirofano, users as usuario_listado_modifico, users as usuario_listado_crea, cirugia_medico_grupo , users as usuario_medico_opera, users as usuario_medico_ayuda, users as usuario_medico_anestesista
       WHERE cirugia_ficha.paciente_id = paciente.id AND users.id = cirugia_ficha.medico_deriva_id AND users.id = medico_deriva.usuario_id AND cirugia_ficha.estado_cirugia_id =  cirugia_estado.id AND paciente.obra_social_id = obra_social.id  AND paciente.coseguro_id = coseguro.id AND cirugia_ficha.usuario_modifico_id = usuario_modifico.id AND cirugia_ficha.usuario_audito = usuario_audito.id AND cirugia_listado_quirofano.usuario_crea_id = usuario_listado_crea.id AND cirugia_listado_quirofano.usuario_modifica_id = usuario_listado_modifico.id AND cirugia_ficha.id = cirugia_lente.cirugia_id AND cirugia_lente.lente_id = lente.id AND lente.tipo_lente_id = lente_tipo.id AND cirugia_listado_quirofano.cirugia_ficha_id = cirugia_ficha.id 
       AND  cirugia_ficha.cirugia_medico_grupo_id = cirugia_medico_grupo.id AND usuario_medico_opera.id = cirugia_medico_grupo.medico_opera_id AND usuario_medico_ayuda.id = cirugia_medico_grupo.medico_ayuda_id AND usuario_medico_anestesista.id = cirugia_medico_grupo.medico_anestesista_id AND cirugia_estado.estado  != 'REALIZADO'  AND fecha_hora BETWEEN '".$fecha."  00:00:00' AND '".$fecha." 23:59:59'  ORDER BY orden ASC
         "), array(
         'estado' =>'PENDIENTE'
       ));
      return response()->json($horario, 201);
     }


     public function updateListadoQuirofano(Request $request,$id){

        $tmp_fecha = str_replace('/', '-', $request['fecha_hora']);
        $fecha_hora =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 

        $update = DB::table('cirugia_listado_quirofano') 
        ->where('id', $id) ->limit(1) 
        ->update( [ 
         'orden' => $request['orden'],       
         'fecha_hora' => $fecha_hora,              
         'usuario_modifica_id' => $request['usuario_modifica_id'],
         'observacion' => $request['quirofano_observacion'],
         'tiene_observacion' => $request['tiene_observacion'],
         'updated_at' => date("Y-m-d H:i:s")     ]);          

         $update = DB::table('cirugia_ficha') 
        ->where('id', $request['cirugia_ficha_id']) ->limit(1) 
        ->update( [ 
         'ojo' => $request['ojo'],       
         'cirugia_practica' => $request['cirugia_practica'],       
         'updated_at' => date("Y-m-d H:i:s")     ]);   

         return response()->json($update, 201);      
   
    }

    
    public function createListadoQuirofano(Request $request){

      
        $tmp_fecha = str_replace('/', '-', $request->fecha_hora);
        $fecha_hora =  date('Y-m-d H:i:s', strtotime($tmp_fecha));  
    
        $id= DB::table('cirugia_listado_quirofano')->insertGetId([
            'cirugia_ficha_id' => $request->id,
            'fecha_hora' => $fecha_hora,                                            
            'orden' => $request->orden,                 
            'usuario_crea_id' => $request->usuario_crea_id,    
            'usuario_modifica_id' => $request->usuario_modifica_id,   
            'fecha_ultima_modificacion' => date("Y-m-d H:i:s"),   
            'tiene_observacion' => 'NO',
            'estado' => "PENDIENTE",   
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);
        
        return response()->json($id, 201);        
       
        }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

   
}



/**SELECT CONCAT(medico_deriva.apellido ,' ', medico_deriva.nombre) as medico_deriva,cirugia_ficha.cirugia_medico_grupo_id, cirugia_ficha.id as cirugia_ficha_id, cirugia_ficha.paciente_id, cirugia_ficha.medico_deriva_id, fecha_derivacion, estado_cirugia_id, paciente.apellido as paciente_apellido, paciente.nombre as paciente_nombre,
         paciente.dni as paciente_dni, paciente.fecha_nacimiento paciente_fecha_nacimiento, cirugia_estado.estado,  obra_social.nombre as obra_social_nombre, obra_social.id as obra_social_id, ojo, cirugia_practica, usuario_modifico.nombreyapellido as cirugia_ficha_usuario_modifico, cirugia_ficha.usuario_modifico_id, usuario_audito.nombreyapellido as cirugia_ficha_usuario_audito, cirugia_ficha.usuario_audito,
         cirugia_listado_quirofano.orden, cirugia_listado_quirofano.usuario_crea_id, usuario_listado_crea.nombreyapellido as usuario_listado_creo_nombre, cirugia_listado_quirofano.usuario_modifica_id ,usuario_listado_modifico.nombreyapellido as usuario_listado_modifico_nombre, lente.dioptria, lente.fecha_vencimiento as lente_vencimiento, lente.lote, lente.estado as lente_estado, lente_tipo.tipo as lente_tipo, usuario_medico_opera.nombreyapellido as usuario_medico_opera_nombre, usuario_medico_ayuda.nombreyapellido as usuario_medico_ayuda_nombre, usuario_medico_anestesista.nombreyapellido as usuario_medico_anestesista_nombre
        FROM cirugia_ficha,paciente ,cirugia_estado, medicos as medico_deriva, users, obra_social,  cirugia_lente, lente, lente_tipo, users as usuario_modifico, users as usuario_audito, cirugia_listado_quirofano, users as usuario_listado_modifico, users as usuario_listado_crea, cirugia_medico_grupo , users as usuario_medico_opera, users as usuario_medico_ayuda, users as usuario_medico_anestesista
        WHERE cirugia_ficha.paciente_id = paciente.id AND users.id = cirugia_ficha.medico_deriva_id AND users.id = medico_deriva.usuario_id AND cirugia_ficha.estado_cirugia_id =  cirugia_estado.id AND paciente.obra_social_id = obra_social.id AND cirugia_ficha.usuario_modifico_id = usuario_modifico.id AND cirugia_ficha.usuario_audito = usuario_audito.id AND cirugia_listado_quirofano.usuario_crea_id = usuario_listado_crea.id AND cirugia_listado_quirofano.usuario_modifica_id = usuario_listado_modifico.id AND cirugia_ficha.id = cirugia_lente.cirugia_id AND cirugia_lente.lente_id = lente.id AND lente.tipo_lente_id = lente_tipo.id AND cirugia_listado_quirofano.cirugia_ficha_id = cirugia_ficha.id AND  cirugia_ficha.cirugia_medico_grupo_id = cirugia_medico_grupo.id AND usuario_medico_opera.id = cirugia_medico_grupo.medico_opera_id AND usuario_medico_ayuda.id = cirugia_medico_grupo.medico_ayuda_id AND usuario_medico_anestesista.id = cirugia_medico_grupo.medico_anestesista_id AND cirugia_estado.estado ='PENDIENTE'  ORDER BY fecha_derivacion DESC */