<?php

namespace App\Http\Controllers\Paciente;

use App\models\Paciente;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class PacienteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       /* try{
            $horario = DB::select( DB::raw("SELECT paciente.id,dni,
            apellido,
            paciente.nombre,
            domicilio,
            sexo,
            fecha_nacimiento,
            ciudad,
            telefono_fijo,
            telefono_cel,
            email,
            tiene_whatsapp,
            obra_social_id, 
            obra_social.es_habilitada,       
            obra_social.nombre as obra_social_nombre,
            coseguro_id,  
            coseguro.nombre as coseguro_nombre,
            obra_social.entidad_factura_id,
            obra_social.tiene_distribucion,
            obra_social.es_coseguro,
            coseguro.tiene_distribucion as coseguro_tiene_distribucion,
            coseguro.es_coseguro as coseguro_es_coseguro,
            entidad.nombre as entidad_nombre,
            numero_afiliado,
            barra_afiliado
            FROM paciente,obra_social,entidad,obra_social as coseguro WHERE obra_social.id= paciente.obra_social_id AND coseguro.id =paciente.coseguro_id AND entidad.id = obra_social.entidad_factura_id
        limit 10000"));
       }catch (\Exception $e) {
        $e->getMessage();
     //  return $this->showOne($e, 500);
       return response()->json($e, 500);
    }
         return response()->json($horario, 201);*/
    }



    public function pacienteAndObraSocialEsHabilitada(Request $request)
    {
        

        $consulta =$request->input('consulta');
        $id =$request->input('id');
        $valor =$request->input('valor');

        $res = DB::select( DB::raw("SELECT 
        paciente.id,
        gravado_adherente,
        dni,
        apellido,
        paciente.nombre,
        domicilio,
        sexo,
        fecha_nacimiento,
        ciudad,
        telefono_fijo,
        telefono_cel,
        email,
        tiene_whatsapp,
        obra_social_id,      
        obra_social.es_habilitada,  
        obra_social.nombre as obra_social_nombre,
        coseguro_id,  
        coseguro.nombre as coseguro_nombre,
        obra_social.entidad_factura_id,
        obra_social.tiene_distribucion,
        obra_social.es_coseguro,
        coseguro.tiene_distribucion as coseguro_tiene_distribucion,
        coseguro.es_coseguro as coseguro_es_coseguro,
        entidad.nombre as entidad_nombre,
        numero_afiliado,
        barra_afiliado
        FROM paciente,obra_social,entidad, obra_social as coseguro
        WHERE  obra_social.id = paciente.obra_social_id AND
         coseguro.id= paciente.coseguro_id AND
         es_habilitada = 'S' AND
        entidad.id = obra_social.entidad_factura_id AND
        paciente.id = :paciente_id
        
        AND   ".$consulta."  LIKE '".$valor."%'
    "), array(
        'paciente_id' =>$id  
      ));
             
    return $this->showAll($res);
    }

    
    public function pacienteAndObraSocialEsTodas($id)
    {
        $id =$request->input('id');
        $consulta =$request->input('consulta');
        $valor =$request->input('valor');

        $res = DB::select( DB::raw("SELECT 
        paciente.id,
        gravado_adherente,
        dni,
        apellido,
        paciente.nombre,
        domicilio,
        sexo,
        fecha_nacimiento,
        ciudad,
        telefono_fijo,
        telefono_cel,
        email,
        tiene_whatsapp,
        obra_social_id,      
        obra_social.es_habilitada,  
        obra_social.nombre as obra_social_nombre,
        coseguro_id,  
        coseguro.nombre as coseguro_nombre,
        obra_social.entidad_factura_id,
        obra_social.tiene_distribucion,
        obra_social.es_coseguro,
        coseguro.tiene_distribucion as coseguro_tiene_distribucion,
        coseguro.es_coseguro as coseguro_es_coseguro,
        entidad.nombre as entidad_nombre,
        numero_afiliado,
        barra_afiliado
        FROM paciente,obra_social,entidad, obra_social as coseguro
        WHERE  obra_social.id = paciente.obra_social_id AND
         coseguro.id= paciente.coseguro_id AND
        entidad.id = obra_social.entidad_factura_id 
        AND
        paciente.id = :paciente_id
        AND   ".$consulta."  LIKE '".$valor."%'
    "), array(
        'paciente_id' =>$id  
      ));
       
      return response()->json($res, 201);
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
            'dni'=> 'required',
            'apellido' => 'required',
            'nombre' => 'required',
            'domicilio' => 'required',
            'sexo' => 'required',
            'fecha_nacimiento' => 'required',
            'tiene_whatsapp' => 'required',
            'ciudad' => 'required',
            'telefono_fijo' => 'required',
            'telefono_cel' => 'required',
            'email' => 'required',
            'obra_social_id' => 'required',
            'coseguro_id' => 'required',
            'numero_afiliado' => 'required',
            'barra_afiliado' => 'required',
            'plan' => 'required',
            'gravado_adherente' => 'required',
        ];

        $this->validate($request, $rules);
        $request['fecha_nacimiento'] = date('Y-m-d', strtotime($request->fecha_nacimiento)); 
      $id= DB::table('paciente')->insertGetId([
            
        'dni'=> $request->dni,
        'apellido' => $request->apellido,
        'nombre' => $request->nombre,
        'domicilio' => $request->domicilio,
        'sexo' => $request->sexo,
        'fecha_nacimiento' => $request->fecha_nacimiento,
        'tiene_whatsapp' => $request->tiene_whatsapp,
        'ciudad' => $request->ciudad,
        'telefono_fijo' => $request->telefono_fijo,
        'telefono_cel' => $request->telefono_cel,
        'email' => $request->email,
        'obra_social_id' => $request->obra_social_id,
        'coseguro_id' => $request->coseguro_id,
        'numero_afiliado' => $request->numero_afiliado,
        'barra_afiliado' => $request->barra_afiliado,
        'plan' => $request->plan,
        'usuario_alta_id' => $request->usuario_alta_id, 
        'gravado_adherente' => $request->gravado_adherente,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")    
    ]);
    $resp = Paciente::find($id);
        return $this->showOne($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

     

        $res = DB::select( DB::raw("SELECT 
        paciente.id,
        gravado_adherente,
        dni,
        apellido,
        paciente.nombre,
        domicilio,
        sexo,
        fecha_nacimiento,
        ciudad,
        telefono_fijo,
        telefono_cel,
        email,
        tiene_whatsapp,
        obra_social_id,      
        obra_social.es_habilitada,  
        obra_social.nombre as obra_social_nombre,
        coseguro_id,  
        coseguro.nombre as coseguro_nombre,
        obra_social.entidad_factura_id,
        obra_social.tiene_distribucion,
        obra_social.es_coseguro,
        coseguro.tiene_distribucion as coseguro_tiene_distribucion,
        coseguro.es_coseguro as coseguro_es_coseguro,
        entidad.nombre as entidad_nombre,
        numero_afiliado,
        barra_afiliado,
        plan
        FROM paciente,obra_social,entidad, obra_social as coseguro
        WHERE  obra_social.id = paciente.obra_social_id AND
         coseguro.id= paciente.coseguro_id AND
        entidad.id = obra_social.entidad_factura_id 
        
        AND  paciente.id = ".$id."
    "));
       
      return response()->json($res, 201);

        
    }
    public function getPacienteByQuery(Request $request)
    {
        $consulta =$request->input('consulta');
        $valor =$request->input('valor');

        $res = DB::select( DB::raw("SELECT 
        paciente.id,
        gravado_adherente,
        dni,
        apellido,
        paciente.nombre,
        domicilio,
        sexo,
        fecha_nacimiento,
        ciudad,
        telefono_fijo,
        telefono_cel,
        email,
        tiene_whatsapp,
        obra_social_id,      
        obra_social.es_habilitada,  
        obra_social.nombre as obra_social_nombre,
        coseguro_id,  
        coseguro.nombre as coseguro_nombre,
        obra_social.entidad_factura_id,
        obra_social.tiene_distribucion,
        obra_social.es_coseguro,
        coseguro.tiene_distribucion as coseguro_tiene_distribucion,
        coseguro.es_coseguro as coseguro_es_coseguro,
        entidad.nombre as entidad_nombre,
        numero_afiliado,
        barra_afiliado,
        plan
        FROM paciente,obra_social,entidad, obra_social as coseguro
        WHERE  obra_social.id = paciente.obra_social_id AND
         coseguro.id= paciente.coseguro_id AND
        entidad.id = obra_social.entidad_factura_id 
        
        AND   ".$consulta."  LIKE '".$valor."%'
    "));
       
      return response()->json($res, 201);

        
    }


    public function getPacienteByDni(Request $request)
    {
        $dni =$request->input('dni');        

        $res = DB::select( DB::raw("SELECT 
        paciente.id,
        gravado_adherente,
        dni,
        apellido,
        paciente.nombre,
        domicilio,
        sexo,
        fecha_nacimiento,
        ciudad,
        telefono_fijo,
        telefono_cel,
        email,
        tiene_whatsapp,
        obra_social_id,      
        obra_social.es_habilitada,  
        obra_social.nombre as obra_social_nombre,
        coseguro_id,  
        coseguro.nombre as coseguro_nombre,
        obra_social.entidad_factura_id,
        obra_social.tiene_distribucion,
        obra_social.es_coseguro,
        coseguro.tiene_distribucion as coseguro_tiene_distribucion,
        coseguro.es_coseguro as coseguro_es_coseguro,
        entidad.nombre as entidad_nombre,
        numero_afiliado,
        barra_afiliado,
        plan
        FROM paciente,obra_social,entidad, obra_social as coseguro
        WHERE  obra_social.id = paciente.obra_social_id AND
         coseguro.id= paciente.coseguro_id AND
        entidad.id = obra_social.entidad_factura_id 
        
        AND   dni = ".$dni."
    "));
       
      return response()->json($res, 201);

        
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      
$tmp_fecha = str_replace('/', '-', $request->input('fecha_nacimiento'));
$fecha_nacimiento =  date('Y-m-d', strtotime($tmp_fecha));         
    $update = DB::table('paciente') 
    ->where('id', $id) ->limit(1) 
    ->update( [ 

        'dni' => $request['dni'],
            'apellido' => $request['apellido'],
            'nombre' => $request['nombre'],
            'domicilio' => $request['domicilio'],
            'sexo' => $request['sexo'],
            'fecha_nacimiento' => $fecha_nacimiento,
            'tiene_whatsapp' => $request['tiene_whatsapp'],
            'ciudad' => $request['ciudad'],
            'telefono_fijo' => $request['telefono_fijo'],
            'telefono_cel' => $request['telefono_cel'],
            'email' => $request['email'],     
            'obra_social_id' => $request['obra_social_id'],
            'coseguro_id' => $request['coseguro_id'],   
            'numero_afiliado' => $request['numero_afiliado'],
            'barra_afiliado' => $request['barra_afiliado'], 
            'plan' => $request['plan'],
            'gravado_adherente' => $request['gravado_adherente'], 
            //'plan' => "1",
           // 'usuario_alta' =1 ,
            'updated_at' => date("Y-m-d H:i:s")  
      ]); 
     $operacionCobroPractica = Paciente::findOrFail($id);
    return $this->showOne($operacionCobroPractica);        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paciente $paciente)
    {
        //
    }
}
