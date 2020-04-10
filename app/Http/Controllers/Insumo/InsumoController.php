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


    
    public function getInsumo(Request $request)
    {
      $res =  DB::select( DB::raw("SELECT * FROM insumo WHERE estado = 'ACTIVO'"));        
       return response()->json($res, 201);
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
            'fecha_vencimiento' => $request->fecha_vencimiento,
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

    
     
    public function getInsumoStock(Request $request)
    {
      $res =  DB::select( DB::raw("SELECT insumo_stock.id, insumo_stock.insumo_id, lote,fecha_vencimiento,  insumo_stock.fecha_ingreso, fecha_modificacion, usuario_id, cantidad_original, cantidad_usada, cantidad_existente, usuario_modifica, insumo.insumo_descripcion, insumo.cantidad, users.nombreyapellido 
      FROM insumo_stock, insumo, users 
      WHERE  insumo_stock.insumo_id = insumo.id AND insumo_stock.usuario_id = users.id AND insumo_stock.cantidad_existente >0 ORDER BY  insumo_stock.fecha_ingreso ASC"));        
       return response()->json($res, 201);
    }



    public function crearInsumoStockMovimiento(Request $request )
    {

/* ---------------------- INSERTO EL REGISTRO DE INSUMO --------------------- */

        $tmp_fecha = str_replace('/', '-', $request->fecha_ingreso);
        $fecha_ingreso =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 

        
     
             $insumo_stock_movimiento= DB::table('insumo_stock_movimiento')->insertGetId([
                 'insumo_id' => $request->insumo_id,
                 'usuario_id' => $request->usuario_id,
                 'cirugia_id' => $request->cirugia_id,
                 'fecha_ingreso' => $fecha_ingreso,            
                 'insumo_stock_id' => $request->insumo_stock_id,                    
                 'cantidad_existente' => $request->cantidad_existente -$request->cantidad_usada,
                 'cantidad_usada' => $request->cantidad_usada,
                 'created_at' => date("Y-m-d H:i:s"),
                 'updated_at' => date("Y-m-d H:i:s")    

                ]); 

/* ----     ------- SI LA SUMA DA MAYOR A  0 GUARDO SINO DEVUELVO ERROR ---------- */
           

                     $update = DB::table('insumo_stock') 
                             ->where('id', '=',  $request->insumo_stock_id)            
                             ->update( [ 
                             'cantidad_usada' =>  $request->cantidad_usada_insumo +   $request->cantidad_usada,
                             'cantidad_existente' => $request->cantidad_existente - $request->cantidad_usada,
                             'usuario_modifica' => $request->usuario_id, 
                             'fecha_modificacion' => date("Y-m-d H:i:s"),                                    
                             'updated_at' => date("Y-m-d H:i:s")   
                     ]);  
       

       return response()->json($update, 201);
    }
  


    public function getInsumoStockMovimiento(Request $request)
    {
      $insumo_stock_id =$request->input('insumo_stock_id');

      $res =  DB::select( DB::raw("SELECT insumo_stock.id, insumo_stock.insumo_id, insumo_stock_movimiento.id AS  insumo_stock_movimiento_id, lote,fecha_vencimiento,  insumo_stock.fecha_ingreso, fecha_modificacion, insumo_stock_movimiento.usuario_id, cantidad_original, insumo_stock.cantidad_usada, insumo_stock.cantidad_existente, usuario_modifica, insumo.insumo_descripcion, insumo.cantidad, users.nombreyapellido, insumo_stock_movimiento.cirugia_id, insumo_stock_movimiento.fecha_ingreso as  insumo_stock_movimiento_fecha_ingreso, insumo_stock_movimiento.cantidad_usada AS insumo_stock_movimiento_cantidad_usada, insumo_stock_movimiento.cantidad_existente as insumo_stock_movimiento_cantidad_existente 
      FROM insumo_stock, insumo, users, insumo_stock_movimiento 
      WHERE  insumo_stock.insumo_id = insumo.id AND insumo_stock.usuario_id = users.id AND insumo_stock.id = :insumo_stock_id  ORDER BY  insumo_stock_movimiento.fecha_ingreso DESC"),
       array(
        'insumo_stock_id' => $insumo_stock_id   
      ));        
       return response()->json($res, 201);
    }
}
