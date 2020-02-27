<?php

namespace App\Http\Controllers\Medico;

use App\models\MedicoObraSocial;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class MedicoObraSocialController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res= DB::table('medicos_os','obra_social','medicos')
        ->join('obra_social','obra_social.id', '=','medicos_os.obra_social_id' )        
        ->join('medicos', 'medicos.id', '=', 'medicos_os.medico_id')
        ->select( 
                'medicos_os.id',             
                'medicos_os.medico_id as medico_id',
                'medicos_os.es_habilitada',
                 'medicos_os.obra_social_id',
                 'medicos.fecha_matricula', 
                 'medicos.nombre as medico_nombre',
                 'medicos.apellido as medico_apellido',
                 'medicos.codigo_old',
                 'obra_social.id as obra_social_id',
                 'obra_social.nombre as obra_social_nombre'                 
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
            'medico_id'=> 'required',
            'obra_social_id'=> 'required',               
            'es_habilitada'=> 'required',
           
           
            ];
    
            $this->validate($request, $rules);
    
            $id= DB::table('medicos_os')->insertGetId([
                
                'obra_social_id' => $request->obra_social_id ,
                'medico_id' => $request->medico_id ,
                'es_habilitada' => $request->es_habilitada,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),            
    
            ]);
          //  echo id;
            $data = MedicoObraSocial::find($id);
            return $this->showOne($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\MedicoObraSocial  $medicoObraSocial
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $res= DB::table('medicos_os','obra_social','medicos','users')
        ->join('obra_social','obra_social.id', '=','medicos_os.obra_social_id' )        
        ->join('medicos', 'medicos.id', '=', 'medicos_os.medico_id')
        ->join('users', 'users.id', '=', 'medicos.usuario_id')
        ->select( 
                'medicos_os.id',             
                'medicos_os.medico_id as medico_id',
                'medicos_os.es_habilitada',
                'medicos_os.obra_social_id',
                'medicos.fecha_matricula', 
                'medicos.nombre as medico_nombre',
                'medicos.apellido as medico_apellido',
                'medicos.codigo_old',
                'obra_social.id as obra_social_id',
                'obra_social.nombre as obra_social_nombre',
                'users.id as usuario_id'
                 )
                 ->where('medicos_os.id','=', $id)
                 ->where('medicos.es_medico_activo','=', 'SI')
                 ->get();
                
    return $this->showAll($res);
    }


    
    public function byIdMedicoTodos()
    {
       
        
        $res= DB::table('medicos','users')      
        ->join('users', 'users.id', '=', 'medicos.usuario_id')
        ->select(       
        'medicos.nombre',
        'medicos.apellido',
        'medicos.fecha_matricula',
        'medicos.codigo_old',
        'users.id as usuario_id'
        )       
        ->where('medicos.es_medico_activo','=', 'SI')
        ->get();
        return $this->showAll($res);
    }


    public function byIdMedico( $id)
    {
        $res= DB::table('medicos_os','obra_social','medicos','users')
        ->join('obra_social','obra_social.id', '=','medicos_os.obra_social_id' )        
        ->join('medicos', 'medicos.id', '=', 'medicos_os.medico_id')
        ->join('users', 'users.id', '=', 'medicos.usuario_id')
        ->select( 
                'medicos_os.id',      
                'medicos_os.es_habilitada',      
                'medicos.fecha_matricula', 
                'medicos.codigo_old',
                'medicos_os.medico_id as medico_id',
                 'medicos_os.obra_social_id',
                 'medicos.nombre as medico_nombre',
                 'medicos.apellido as medico_apellido',
                 'obra_social.id as obra_social_id',
                 'obra_social.nombre as obra_social_nombre',
                 'users.id as usuario_id'               
                 )
                 ->where('medicos_os.medico_id','=', $id)
                 ->where('medicos.es_medico_activo','=', 'SI')
                 ->get();
                
    return $this->showAll($res);
    }

    public function byIdMedicoHabilitado( $medico_id)
    {
        $medico_id = $request->input('medico_id'); 
        
        $res= DB::table('medicos_os','obra_social','medicos','users')
        ->join('obra_social','obra_social.id', '=','medicos_os.obra_social_id' )        
        ->join('medicos', 'medicos.id', '=', 'medicos_os.medico_id')
        ->join('users', 'users.id', '=', 'medicos.usuario_id')
        ->select( 
                'medicos_os.id as id',  
                'medicos_os.es_habilitada',       
                'medicos_os.medico_id as medico_id',
                 'medicos_os.obra_social_id',
                 'medicos.fecha_matricula', 
                 'medicos.codigo_old',
                 'medicos.nombre as medico_nombre',
                 'medicos.apellido as medico_apellido',
                 'obra_social.id as obra_social_id',
                 'obra_social.nombre as obra_social_nombre',
                 'users.id as usuario_id'      
                 )
                 ->where([
                    ['medicos_os.es_habilitada','=', 'S'],
                    ['medicos_os.medico_id','=', $medico_id],
                    ['medicos.es_medico_activo','=', 'SI'],                    
                ])
                 ->get();
                
    return $this->showAll($res);
    }

    
    public function getMedicoByObraSocialHabilitado(Request $request )
    {
               
        $obra_social_id = $request->input('obra_social_id');        

        $res= DB::table('medicos_os','obra_social','medicos','users')
        ->join('medicos', 'medicos.id', '=', 'medicos_os.medico_id')
        ->join('obra_social', 'obra_social.id', '=', 'medicos_os.obra_social_id')
        ->join('users', 'users.id', '=', 'medicos.usuario_id')
        ->select(
        'medicos_os.id as id',
        'medicos_os.medico_id',
        'medicos_os.obra_social_id',
        'medicos.fecha_matricula', 
        'medicos.codigo_old', 
        'obra_social.nombre as obra_social_nombre',
        'medicos.nombre',
        'medicos.apellido',
        'users.id as usuario_id'
        )
        ->where('obra_social.es_habilitada','=','S')                    
        ->where('medicos_os.obra_social_id','=',$obra_social_id)  
        ->where('medicos.es_medico_activo','=', 'SI')
        ->get();
        return $this->showAll($res);
    }

    public function getObraSocialByMedicoHabilitado(Request $request )
    {
               
        $medico_id = $request->input('medico_id');        
        $res= DB::table('medicos_os','obra_social','medicos','users')
        ->join('medicos', 'medicos.id', '=', 'medicos_os.medico_id')
        ->join('obra_social', 'obra_social.id', '=', 'medicos_os.obra_social_id')
        ->join('users', 'users.id', '=', 'medicos.usuario_id')
       // ->join('medicos as', 'medicos.usuario_id', '=', 'users.id')
        ->select(
        'medicos_os.id as id',
        'medicos_os.medico_id',
        'medicos_os.obra_social_id',
        'medicos.fecha_matricula', 
        'obra_social.nombre as obra_social_nombre',
        'medicos.nombre',
        'medicos.apellido',
        'medicos.codigo_old',
        'users.id as usuario_id'
        )
        ->where('obra_social.es_habilitada','=','S')                            
        ->where('medicos_os.medico_id','=',$medico_id)  
        ->where('medicos.es_medico_activo','=', 'SI')
        ->get();
       //echo($medico_id);
        return $this->showAll($res);
    }


    
    public function getObraSocialByMedicoTodos(Request $request )
    {
               
        $medico_id = $request->input('medico_id');   
          
        $res= DB::table('medicos_os','obra_social','medicos','users')
        ->join('medicos', 'medicos.id', '=', 'medicos_os.medico_id')
        ->join('obra_social', 'obra_social.id', '=', 'medicos_os.obra_social_id')
        ->join('users', 'users.id', '=', 'medicos.usuario_id')
        ->select(
        'medicos_os.id as id',
        'medicos_os.medico_id',
        'medicos_os.obra_social_id',
        'medicos.fecha_matricula', 
        'obra_social.nombre as obra_social_nombre',
        'medicos.nombre',
        'medicos.apellido',
        'medicos.codigo_old',
        'users.id as usuario_id'
        )                   
        ->where('medicos.es_medico_activo','=', 'SI')   
      //  ->where('medicos_os.medico_id','=',$medico_id)  
        ->get();
        return $this->showAll($res);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\MedicoObraSocial  $medicoObraSocial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $medicoObraSocial = MedicoObraSocial::findOrFail($id);
        $medicoObraSocial->fill($request->only([
            'obra_social_id',
            'medico_id',
            'es_habilitada',                          
    ]));
    //var_dump($medicoObraSocial);

   if ($medicoObraSocial->isClean()) {
        return $this->errorResponse('Se debe especificar al menos un valor', 422);
    }
   $medicoObraSocial->save();
    return $this->showOne($medicoObraSocial);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\MedicoObraSocial  $medicoObraSocial
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('medicos_os')->where('id', '=', $id)->delete();
        return $this->errorResponse('Registro eliminado', 201);
    }
}
