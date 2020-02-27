<?php

namespace App\Http\Controllers\ObraSocial;

use App\models\ConvenioObraSocialPmo;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ConvenioObraSocialController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { // FALTA ENTIDAD FACTURA
        $res= DB::table('convenio_os_pmo','obra_social','pmo')
        ->join('obra_social', 'obra_social.id', '=', 'convenio_os_pmo.obra_social_id')
        ->join('pmo', 'pmo.id', '=', 'convenio_os_pmo.pmo_id')
        ->select('convenio_os_pmo.id',        
        'convenio_os_pmo.valor',
        'obra_social.id as obra_social_id',
        'obra_social.nombre as obra_social_nombre',
        'obra_social.es_habilitada',
        'pmo.id as pmo_id',
        'pmo.codigo',
        'pmo.descripcion as pmo_descripcion',
        'convenio_os_pmo.es_habilitado',
        'pmo.complejidad')
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
        $id= DB::table('convenio_os_pmo')->insertGetId([
            
            'obra_social_id' => $request->obra_social_id,
            'pmo_id' => $request->pmo_id,
            'valor' => $request->valor,
            'es_habilitado' => 'S',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
        ]);
        $resp = ConvenioObraSocialPmo::find($id);
        return $this->showOne($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\ConvenioObraSocialPmo  $convenioObraSocialPmo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res= DB::table('convenio_os_pmo','obra_social','entidad','pmo')
        ->join('obra_social', 'obra_social.id', '=', 'convenio_os_pmo.obra_social_id')
        ->join('entidad', 'entidad.id', '=', 'convenio_os_pmo.entidad_factura_id')
        ->join('pmo', 'pmo.id', '=', 'convenio_os_pmo.pmo_id')
        ->select('convenio_os_pmo.id',       
        'convenio_os_pmo.valor',
        'obra_social.id as obra_social_id',
        'obra_social.nombre as obra_social_nombre',
        'obra_social.es_habilitada',
        'pmo.id as pmo_id',
        'pmo.codigo',
        'pmo.descripcion as pmo_descripcion',
        'pmo.complejidad',
        'obra_social.tiene_distribucion')
        ->where('convenio_os_pmo.id','=', $id)
        ->where('convenio_os_pmo.es_habilitado','=', 'S')
        ->orderBy('convenio_os_pmo.id', 'desc')
        ->get();
    return $this->showAll($res);
    }



    public function findByObraSocial($id)
    {
        $res= DB::table('convenio_os_pmo','obra_social','entidad','pmo')
        ->join('obra_social', 'obra_social.id', '=', 'convenio_os_pmo.obra_social_id')
        ->join('entidad', 'entidad.id', '=', 'obra_social.entidad_factura_id')
        ->join('pmo', 'pmo.id', '=', 'convenio_os_pmo.pmo_id')
        ->select('convenio_os_pmo.id',       
        'convenio_os_pmo.valor',
        'obra_social.id as obra_social_id',
        'obra_social.nombre as obra_social_nombre',
        'obra_social.es_habilitada',
        'pmo.id as pmo_id',
        'pmo.codigo',
        'pmo.descripcion as pmo_descripcion',
        'pmo.complejidad',
        'obra_social.tiene_distribucion')
        ->where('convenio_os_pmo.obra_social_id','=', $id)
        ->where('convenio_os_pmo.es_habilitado','=', 'S')
        ->get();
    return $this->showAll($res);
    }

    public function findByPmo($id)
    {
        $res= DB::table('convenio_os_pmo','obra_social','entidad','pmo')
        ->join('obra_social', 'obra_social.id', '=', 'convenio_os_pmo.obra_social_id')
        ->join('entidad', 'entidad.id', '=', 'obra_social.entidad_factura_id')
        ->join('pmo', 'pmo.id', '=', 'convenio_os_pmo.pmo_id')
        ->select('convenio_os_pmo.id',       
        'convenio_os_pmo.valor',
        'obra_social.id as obra_social_id',
        'obra_social.nombre as obra_social_nombre',
        'obra_social.es_habilitada',
        'pmo.id as pmo_id',
        'pmo.codigo',
        'pmo.descripcion as pmo_descripcion',
        'pmo.complejidad',
        'obra_social.tiene_distribucion')
        ->where('convenio_os_pmo.pmo_id','=', $id)
        ->where('convenio_os_pmo.es_habilitado','=', 'S')
        ->get();
    return $this->showAll($res);
    }
  
   


    public function  findByObraSocialAndCoseguro(Request $request)
    {
        $obra_social_id = $request->obra_social_id;
        $coseguro_id = $request->coseguro_id;
        
        $res= DB::table('convenio_os_pmo','obra_social','entidad','pmo')
        ->join('obra_social', 'obra_social.id', '=', 'convenio_os_pmo.obra_social_id')        
        ->join('entidad', 'entidad.id', '=', 'obra_social.entidad_factura_id')
        ->join('pmo', 'pmo.id', '=', 'convenio_os_pmo.pmo_id')
        ->select(
        'convenio_os_pmo.id',       
        'convenio_os_pmo.valor',
        'obra_social.id as obra_social_id',
        'obra_social.nombre as obra_social_nombre',
        'obra_social.es_habilitada',
        'pmo.id as pmo_id',
        'pmo.codigo',
        'pmo.descripcion as pmo_descripcion',
        'pmo.complejidad',
        'obra_social.es_coseguro',
        'obra_social.tiene_distribucion')       
        ->where('convenio_os_pmo.es_habilitado','=', 'S')
        ->where('obra_social.es_habilitada','=', 'S')
        ->whereIn('obra_social.id', [$obra_social_id,$coseguro_id,86])
        ->get();
   return $this->showAll($res);
  
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\ConvenioObraSocialPmo  $convenioObraSocialPmo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
     

        $convenioObraSocialPmo = ConvenioObraSocialPmo::findOrFail($id);
        $convenioObraSocialPmo->fill($request->only([
            'obra_social_id',
            'pmo_id',         
            'valor',
            'es_habilitado'
    ]));


  /* if ($convenioObraSocialPmo->isClean()) {
        return $this->errorResponse('Se debe especificar al menos un valor', 422);
    }*/
      $update = DB::table('convenio_os_pmo') 
        ->where('id', $request['id']) ->limit(1) 
        ->update( [ 'obra_social_id' => $request['obra_social_id'],
         'pmo_id' => $request['pmo_id'],
          'valor' => $request['valor'],
          'es_habilitado' => $request['es_habilitado'] ]); 
          $resp = ConvenioObraSocialPmo::find($id);
    return $this->showOne($resp);
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\ConvenioObraSocialPmo  $convenioObraSocialPmo
     * @return \Illuminate\Http\Response
     */
    public function  ActualizarValoresDistribucion()
    {
        $horario = DB::statement((" 
        UPDATE practica_distribucion, convenio_os_pmo, obra_social SET practica_distribucion.total  = ((practica_distribucion.porcentaje * convenio_os_pmo.valor) / 100) ,practica_distribucion.valor = convenio_os_pmo.valor  WHERE practica_distribucion.practica_distribucion_id = convenio_os_pmo.id  AND convenio_os_pmo.obra_social_id     = obra_social.id
    "));

             return response()->json($horario, 201);      
   return $this->showAll($res);
  
    }
}
