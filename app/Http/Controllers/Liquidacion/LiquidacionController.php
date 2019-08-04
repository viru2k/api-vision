<?php

namespace App\Http\Controllers\Liquidacion;

use App\models\Liquidacion;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;



/**
 *  datos 
 *  nro | nivel | desde | hasta | os | medico grupo | medico factura
 * 
 * * */
class LiquidacionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res= DB::table('liq_liquidacion','obra_social','users')
        ->join('obra_social','obra_social.id', '=','liq_liquidacion.obra_social_id' )        
        ->join('users', 'users.id', '=', 'liq_liquidacion.usuario_audito')
        ->select(
                 'liq_liquidacion.id',
                 'numero',
                 'fecha_desde',
                 'fecha_hasta',
                 'liquidacion_generada_id' ,
                 'cant_orden',
                 'total',
                 'usuario_audito',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',
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
            'obra_social_id'=> 'required',
            'numero'=> 'required',               
            'fecha_desde'=> 'required',
            'fecha_hasta'=> 'required',
            'cant_orden'=> 'required',
            'usuario_audito'=> 'required',
            'total'=> 'required',
           
            ];
    
            $this->validate($request, $rules);
    
            $id= DB::table('liq_liquidacion')->insertGetId([
                
                'obra_social_id' => $request->obra_social_id ,
                'numero' => $request->numero ,
                'fecha_desde' => $request->fecha_desde,
                'fecha_hasta' => $request->fecha_hasta,
                'liquidacion_generada_id' =>0,
                'cant_orden' => $request->cant_orden,
                'total' => $request->total,
                'usuario_audito' => $request->usuario_audito,
                'estado' =>  $request->estado,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),            
    
            ]);
            $data = Liquidacion::find($id);
            return $this->showOne($data, 201);
    }


       
    /**
     * Display the specified resource.
     *
     * @param  \App\models\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function show(Liquidacion $liquidacion)
    {
        $res= DB::table('liq_liqudacion','obra_social','users')
        ->join('obra_social','obra_social.id', '=','liq_liqudacion.obra_social_id' )        
        ->join('users', 'users.id', '=', 'liq_liqudacion.usuario_audito_id')
        ->select(
                 'liq_liqudacion.id',
                 'numero',
                 'fecha_desde',
                 'fecha_hasta',
                 'liquidacion_generada_id' ,
                 'cant_orden',
                 'total',
                 'usuario_audito',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',
                 'users.name'
                 )
                 ->get();
                
    return $this->showAll($res);
    }

    
    public function byLiquidacionGenerada($liquidacion_generada_id){

      
        $res= DB::table('liq_liqudacion','obra_social','users')
        ->join('obra_social','obra_social.id', '=','liq_liqudacion.obra_social_id' )        
        ->join('users', 'users.id', '=', 'liq_liqudacion.usuario_audito_id')
        ->select(
                 'liq_liqudacion.id',
                 'numero',
                 'fecha_desde',
                 'fecha_hasta',
                 'liquidacion_generada_id' ,
                 'cant_orden',
                 'total',
                 'usuario_audito',
                 'obra_social.id as obra_social_id',
                 'obra_social.id as obra_social_nombre',
                 'users.name'
                 )
        ->where('liquidacion_generada_id','=', $liquidacion_generada_id)
                 ->get();
                
    return $this->showAll($res);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $liquidacion = Liquidacion::findOrFail($id);
        $liquidacion->fill($request->only([
            'obra_social_id',
            'numero',
            'fecha_desde',               
            'fecha_hasta',
            'liquidacion_generada_id',
            'cant_orden',
            'total',
            'estado',
            'usuario_audito',
            'updated_at',
            'created_at'
    ]));
   // var_dump($liquidacion);

   if ($liquidacion->isClean()) {
        return $this->errorResponse('Se debe especificar al menos un valor', 422);
    }
   $liquidacion->save();
    return $this->showOne($liquidacion);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Liquidacion $liquidacion)
    {
        //
    }
}
