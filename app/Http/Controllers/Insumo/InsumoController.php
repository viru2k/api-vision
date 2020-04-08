<?php

namespace App\Http\Controllers\Insumo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\ApiController;

use Illuminate\Support\Facades\DB; 

class InsumoController extends ApiController
{

/* -------------------------------------------------------------------------- */
/*                                CREAR INSUMO                                */
/* -------------------------------------------------------------------------- */

    public function crearInsumo(Request $request)
    {
        $res= DB::table('insumo')->insertGetId([
            'insumo_descripcion' => $request->insumo_descripcion,
            'unidad_id' => $request->unidad_id,            
            'cantidad' => $request->cantidad,            
            'estado' => 'ACTIVO'
            
           ]); 

       return response()->json($res, 201);
    }


    public function actualizarInsumo(Request $request)
    {
        $update = DB::table('insumo') 
       ->where('id', '=',  $request['id'] )
       ->update( [ 
        'insumo_descripcion' => $request['insumo_descripcion'],        
        'unidad_id' => $request['unidad_id'],   
        'cantidad' => $request['cantidad'],   
        ]);

       return response()->json($update, 201);
    }


    public function crearInsumoStock(Request $request)
    {

        $tmp_fecha = str_replace('/', '-', $request->fecha_ingreso);
        $fecha_ingreso =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 

        $sesion_usuario= DB::table('insumo_stock')->insertGetId([
            'insumo_id' => $request->insumo_id,
            'fecha_ingreso' => $fecha_ingreso,            
            'fecha_modificacion' => $date("Y-m-d H:i:s"),  
            'lote' => $request->lote,
            'usuario_id' => $request->usuario_id,            
            'cantidad_original' => $request->cantidad_original,
            'cantidad_usada' => '0',
            'cantidad_existente' => $request->cantidad_original,
            'usuario_modifica' => $request->usuario_id,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    
            
           ]); 

       return response()->json($sesion, 201);
    }


    public function crearInsumoStockMovimiento(Request $request)
    {

/* ---------------------- INSERTO EL REGISTRO DE INSUMO --------------------- */

        $tmp_fecha = str_replace('/', '-', $request->fecha_ingreso);
        $fecha_ingreso =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 

        
        if(($res[0]->cantidad_original -$res[0]->cantidad_usada + $request->cantidad_usada)< 0){
             $insumo_stock_movimieno= DB::table('insumo_stock_movimieno')->insertGetId([
                 'insumo_id' => $request->insumo_id,
                 'usuario_id' => $request->usuario_id,
                 'cirugia_id' => $request->cirugia_id,
                 'fecha_ingreso' => $fecha_ingreso,            
                 'insumo_stock_id' => $request->insumo_stock_id,                    
                 'cantidad_existente' => $request->cantidad_existente,
                 'cantidad_usada' => $request->cantidad_usada,
                 'created_at' => date("Y-m-d H:i:s"),
                 'updated_at' => date("Y-m-d H:i:s")    

                ]); 

                if($insumo_stock_movimieno){
                
                 $res =   DB::select( DB::raw("SELECT * FROM stock_insumo WHERE id = :id"
                 )
                 , array(
                 'id' => $insumo_stock_movimieno   
               ));
           
/* ----     ------- SI LA SUMA DA MAYOR A  0 GUARDO SINO DEVUELVO ERROR ---------- */
           

                     $update = DB::table('insumo_stock') 
                             ->where('id', '=',  $insumo_stock_movimieno)            
                             ->update( [ 
                             'cantidad_usada' => $res[0]->cantidad_usada + $request->cantidad_usada,
                             'cantidad_existente' => $res[0]->cantidad_original -$res[0]->cantidad_usada + $request->cantidad_usada,
                             'usuario_modifica' => $request->usuario_modifica, 
                             'fecha_modificacion' => date("Y-m-d H:i:s"),       
                             'fecha_lectura' => date("Y-m-d H:i:s"),   
                             'updated_at' => date("Y-m-d H:i:s")   
                     ]);  

            }else{
                return response()->json($request, 406);
            }
       
           }

       return response()->json($update, 201);
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
        //
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
