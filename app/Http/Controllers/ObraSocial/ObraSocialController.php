<?php

namespace App\Http\Controllers\ObraSocial;

use App\models\ObraSocial;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ObraSocialController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res= DB::table('obra_social','entidad')
        ->join('entidad', 'entidad.id', '=', 'obra_social.entidad_factura_id')
        ->select(
        'obra_social.id',
        'obra_social.nombre',
        'obra_social.descripcion',
        'obra_social.es_habilitada',
        'obra_social.tiene_distribucion',
        'obra_social.es_coseguro',
        'entidad.id as entidad_id',
        'entidad.nombre as entidad_nombre')
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
             'nombre' => 'required',          
             'descripcion' => 'required',          
             'es_habilitada' => 'required',  
             'entidad_factura_id' => 'required', 
             'tiene_distribucion' => 'required',
            'es_coseguro' => 'required',
            
         ];
 
         $this->validate($request, $rules);
         $resp = ObraSocial::create($request->all());
         return $this->showOne($resp, 201);
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\ObraSocial  $obraSocial
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $res= DB::table('obra_social','entidad')
        ->join('entidad', 'entidad.id', '=', 'obra_social.entidad_factura_id')
        ->select(
        'obra_social.id',
        'obra_social.nombre',
        'obra_social.descripcion',
        'obra_social.es_habilitada',
        'obra_social.tiene_distribucion',
        'obra_social.es_coseguro',
        'entidad.id as entidad_id',
        'entidad.nombre as entidad_nombre')
        ->where('obra_social.id','=', $id)
        ->get();
        return $this->showAll($res);
    }
   



    public function update(Request $request, $id)
    {
        $obraSocial = ObraSocial::findOrFail($id);
        $obraSocial->fill($request->only([
            'nombre',
            'descripcion',
            'es_habilitada',  
            'entidad_factura_id',
            'tiene_distribucion',
            'es_coseguro',
    ]));
         //   var_dump($obraSocial);
   if ($obraSocial->isClean()) {
        return $this->errorResponse('Se debe especificar al menos un valor', 422);
    }
   $obraSocial->save();
     //$res = ObraSocial::update($id);
    return $this->showOne($obraSocial);
    }


    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\ObraSocial  $obraSocial
     * @return \Illuminate\Http\Response
     */
    public function destroy(ObraSocial $obraSocial)
    {
        //
    }

     
    public function obraSocialByIdAndPmoId(Request $request){
        
        $obra_social_id = $request->input('obra_social_id');
        $pmo_id = $request->input('pmo_id');        
        
        $res = DB::table('convenio_os_pmo','pmo', 'obra_social')
        ->join('obra_social', 'obra_social.id', '=', 'convenio_os_pmo.obra_social_id')
        ->join('pmo', 'pmo.id', '=', 'convenio_os_pmo.pmo_id')
        ->select(
            'convenio_os_pmo.id',
            'convenio_os_pmo.es_habilitado',
            'obra_social.id as obra_social_id',
            'pmo.id as pmo_id',
            'obra_social.nombre as obra_social_nombre',
            'obra_social.es_habilitada',
            'obra_social.entidad_factura_id',
            'obra_social.tiene_distribucion',
            'obra_social.es_coseguro',
            'pmo.codigo',
            'pmo.descripcion',
            'pmo.complejidad'
           )
            ->where('convenio_os_pmo.obra_social_id','=',$obra_social_id)            
            ->where('convenio_os_pmo.pmo_id','=',$pmo_id)            
            ->get();
           
        return $this->showAll($res);
}

public function generarCoseguros(){
            
    
    $obra_social = DB::table( 'obra_social')
    ->select(
        'nombre',
        'es_habilitada',        
        'tiene_distribucion',
        'es_coseguro',
        'id'
       )
        ->where('es_coseguro','=','S')                          
        ->get();
      

        $res = DB::table( 'convenio_os_pmo','obra_social')
        ->join('obra_social', 'obra_social.id', '=', 'convenio_os_pmo.obra_social_id')
        ->select(
            'obra_social.id as obra_social_id',
            'obra_social.nombre',
            'obra_social.es_habilitada',            
            'obra_social.tiene_distribucion',
            'obra_social.es_coseguro',
            'convenio_os_pmo.id as convenio_os_pmo_id',
            'convenio_os_pmo.pmo_id',
            'convenio_os_pmo.valor'
           )
            ->where('tiene_distribucion','=','S')                          
            ->get();    
    //var_dump($res[1]["convenio_os_pmo_id"]);
    $tpmo = json_decode($res, true);
    $tobrasocial = json_decode($obra_social, true);
    for($i=0 ;$i< count($tobrasocial); $i++){      
        for($j=0 ;$j< count($tpmo); $j++){      
            $porcentaje = ($tpmo[$j]["valor"]* 20)/80;         
       $id =    DB::table('convenio_os_pmo')->insertGetId(
        ['obra_social_id' => $tobrasocial[$i]["id"],
         'pmo_id' => $tpmo[$j]["pmo_id"],
          'valor' => $porcentaje,
           'es_habilitado' => "S",
           'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
        ]           
        );
       /* echo $tpmo[2]["valor"];
        echo $tpmo[2]["valor"]* 20;
        echo ($tpmo[2]["valor"]* 20)/80;*/
    }
    }
    return response()->json($tobrasocial, 201);
}
    



    
public function actualizarCoseguros(){
            
    
    $obra_social = DB::table( 'obra_social')
    ->select(
        'nombre',
        'es_habilitada',        
        'tiene_distribucion',
        'es_coseguro',
        'id'
       )
        ->where('es_coseguro','=','S')                          
        ->get();
        $tobrasocial = json_decode($obra_social, true);

        $res = DB::table( 'convenio_os_pmo','obra_social')
        ->join('obra_social', 'obra_social.id', '=', 'convenio_os_pmo.obra_social_id')
        ->select(
            'obra_social.id as obra_social_id',
            'obra_social.nombre',
            'obra_social.es_habilitada',            
            'obra_social.tiene_distribucion',
            'obra_social.es_coseguro',
            'convenio_os_pmo.id as convenio_os_pmo_id',
            'convenio_os_pmo.pmo_id',
            'convenio_os_pmo.valor'
           )
            ->where('tiene_distribucion','=','S')                          
            ->get();        
        $tpmo = json_decode($res, true);
    
    for($i=0 ;$i< count($tobrasocial); $i++){      
        for($j=0 ;$j< count($tpmo); $j++){ 

            $porcentaje = ($tpmo[$j]["valor"]* 20)/80;  
            
            $update = DB::table('convenio_os_pmo') 
            ->where('pmo_id','=',$tpmo[$j]["pmo_id"])
            ->where('obra_social_id','=',$tobrasocial[$i]["id"]) ->limit(1) 
            ->update( [   
             'valor' => $porcentaje,  
             'updated_at' => date("Y-m-d H:i:s")     ]);
          //   echo $porcentaje;  
        }
    }    
   return response()->json("valores actualizados", 201);
    }
    
    
    
public function insertarConvenioCoseguro(){
            
    
    $coseguro = DB::table('obra_social')    
    ->select(
        'id',
        'es_coseguro'     
       )
        ->where('es_coseguro','=','S')                          
        ->get();
    $tcoseguro = json_decode($coseguro, true);       

    $coseguro_pmo = DB::table('convenio_os_pmo','obra_social')
    ->join('obra_social', 'obra_social.id', '=', 'convenio_os_pmo.obra_social_id')
    ->select(
        'convenio_os_pmo.id',
        'convenio_os_pmo.pmo_id',        
        'convenio_os_pmo.valor',
        'convenio_os_pmo.obra_social_id',
        'obra_social.es_coseguro'     
       )
        ->where('obra_social.es_coseguro','=','S')                          
        ->get();
   
        $tpmo = json_decode($coseguro_pmo, true);    

    $obra_social_pmo = DB::table( 'convenio_os_pmo')
    ->select(
        'id',
        'obra_social_id',
        'pmo_id',        
        'valor',
        'es_habilitado'
       )
        ->where('obra_social_id','=',1)                          
        ->get();
     //   var_dump( $obra_social_pmo);
        $tobrasocial = json_decode($obra_social_pmo, true);

    $existe = false;
    $pmo_inexistente = 0;
    $os_inexistente = 0;
    $valor_inexistente = 0;
        
    for($i=0 ;$i< count($tobrasocial); $i++){    
      
        for($j=0 ;$j< count($tpmo); $j++){ 

             $pmo_inexistente = $tobrasocial[$i]["pmo_id"];
             $os_inexistente= $tobrasocial[$i]["obra_social_id"];
             $valor_inexistente= $tobrasocial[$i]["valor"];

             if($tpmo[$j]["pmo_id"] == $tobrasocial[$i]["pmo_id"]){
                 $existe = true;
                 $pmo_inexistente = 0;                 
               //  echo "existe";
                 break;
             }
                               
        }
        // luego de buscar si la practica existe la inserto
       
        if($existe == false){        
            $porcentaje = ($valor_inexistente* 20)/80;  
            for($j=0 ;$j< count($tcoseguro); $j++){                
            $id =    DB::table('convenio_os_pmo')->insertGetId(
                ['obra_social_id' => $tcoseguro[$j]["id"],
                'pmo_id' => $pmo_inexistente,
                'valor' => $porcentaje,
                'es_habilitado' => "S",
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
                ]           
                );      
            }
        }
        $existe = false;
        $pmo_inexistente=0;
    }    
    return response()->json("datos insertados", 201);
    }
    
    


}