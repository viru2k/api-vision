<?php

namespace App\Http\Controllers\Practica;


use App\models\PracticaDistribucion;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class PracticaDistribucionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        // FALTA AGREGAR CITA -> AGENDA
        $res= DB::table('practica_distribucion','convenio_os_pmo','obra_social','pmo')        
   ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica_distribucion.convenio_os_pmo_id' )
   ->join('convenio_os_pmo as practica', 'practica.id', '=','practica_distribucion.practica_distribucion_id' )
   ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
   ->join('obra_social as obra_social_practica','obra_social_practica.id', '=','practica.obra_social_id' )
   ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )           
   ->select(
       'practica_distribucion.id',
       'convenio_os_pmo_id',
       'practica_distribucion_id',
       'practica_distribucion.porcentaje as practica_distribucion_porcentaje',
       'practica_distribucion.valor as practica_distribucion_valor',
       'practica_distribucion.total as practica_distribucion_total',           
       'obra_social.id as obra_social_id',
       'obra_social.id as obra_social_nombre',                 
       'convenio_os_pmo.valor as convenio_valor',
       'obra_social.nombre as obra_social_nombre',
       'obra_social_practica.nombre as obra_social_practica_nombre',
        'pmo.codigo'   
       )
                 ->get();
                
    return $this->showAll($res);
    }


    public function bypractica($id){
        
   // FALTA AGREGAR CITA -> AGENDA
   //echo $id;
   $res= DB::table('practica_distribucion','convenio_os_pmo','obra_social','pmo')        
   ->join('convenio_os_pmo', 'convenio_os_pmo.id', '=','practica_distribucion.convenio_os_pmo_id' )
   ->join('convenio_os_pmo as practica', 'practica.id', '=','practica_distribucion.practica_distribucion_id' )
   ->join('obra_social','obra_social.id', '=','convenio_os_pmo.obra_social_id' )
   ->join('obra_social as obra_social_practica','obra_social_practica.id', '=','practica.obra_social_id' )
   ->join('pmo','pmo.id', '=','convenio_os_pmo.pmo_id' )           
   ->select(
       'practica_distribucion.id',
       'convenio_os_pmo_id',
       'practica_distribucion_id',
       'practica_distribucion.porcentaje as practica_distribucion_porcentaje',
       'practica_distribucion.valor as practica_distribucion_valor',
       'practica_distribucion.total as practica_distribucion_total',           
       'obra_social.id as obra_social_id',
       'obra_social.id as obra_social_nombre',                 
       'convenio_os_pmo.valor as convenio_valor',
       'obra_social.nombre as obra_social_nombre',
       'obra_social_practica.nombre as obra_social_practica_nombre',
        'pmo.codigo'   
       )
            
            ->where('practica_distribucion.convenio_os_pmo_id','=', $id)
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
            'convenio_os_pmo_id' => 'required',          
            'practica_distribucion_id' => 'required',          
            'porcentaje' => 'required',  
            'valor' => 'required',  
            'total' => 'required',  
           
        ];

        $this->validate($request, $rules);
        $resp = PracticaDistribucion::create($request->all());
        return $this->showOne($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PracticaDistribucion  $practicaDistribucion
     * @return \Illuminate\Http\Response
     */
    public function show(PracticaDistribucion $practicaDistribucion)
    {
        //
    }

   


    public function update(Request $request, $id)
    {
        $practicaDistribucion = PracticaDistribucion::findOrFail($id);
        $practicaDistribucion->fill($request->only([
            'convenio_os_pmo_id',
            'practica_distribucion_id',
            'porcentaje',  
            'valor',  
            'total'
    ]));
         //   var_dump($obraSocial);
   if ($practicaDistribucion->isClean()) {
        return $this->errorResponse('Se debe especificar al menos un valor', 422);
    }
   $practicaDistribucion->save();     
    return $this->showOne($practicaDistribucion);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PracticaDistribucion  $practicaDistribucion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $practicaDistribucion = PracticaDistribucion::find($id);
        $practicaDistribucion->delete();
        return $this->showOne($practicaDistribucion);
    }
}
