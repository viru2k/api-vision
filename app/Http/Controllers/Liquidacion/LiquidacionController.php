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


    // CREO LA DISTRIBUCION Y LUEGO ACTUALIZO LOS  REGISTROS DE O.C 
    public function liquidacionDistribuir(Request $request)
    {

        $tmp_fecha = str_replace('/', '-', $request["fecha_distribucion"]);
        $fecha_distribucion =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 

        $id= DB::table('liq_liquidacion_distribucion')->insertGetId([
            'medico_opera_id' => $request["medico_opera_id"],
            'medico_opera_porcentaje' => $request["medico_opera_porcentaje"],
            'medico_opera_valor' => $request["medico_opera_valor"],
            'medico_ayuda_id' => $request["medico_ayuda_id"],
            'medico_ayuda_porcentaje' => $request["medico_ayuda_porcentaje"],
            'medico_ayuda_valor' => $request["medico_ayuda_valor"],
            'medico_ayuda2_id' => $request["medico_ayuda2_id"],
            'medico_ayuda2_porcentaje'=> $request["medico_ayuda2_porcentaje"],
            'medico_ayuda2_valor'=> $request["medico_ayuda2_valor"],
            'medico_clinica_id'=> $request["medico_clinica_id"],
            'medico_clinica_porcentaje'=> $request["medico_clinica_porcentaje"],
            'medico_clinica_valor'=> $request["medico_clinica_valor"],
            'valor_distribuido'=> $request["valor_distribuido"],
            'total'=> $request["total"],            
            'usuario_audito'=> $request["usuario_audito"],
            'fecha_distribucion' => $fecha_distribucion,         
            'created_at' => date("Y-m-d H:i:s", strtotime('-3 hours')),
            'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours'))   
        ]);    

        
        $i = 0;
    while(isset($request->operacion_cobro_practica[$i])){       
        $update = DB::table('operacion_cobro_practica')         
        ->where('id',$request->operacion_cobro_practica[$i]["id"]) ->limit(1) 
        ->update( [     
         'usuario_liquida_id' => $request["usuario_audito"],
         'liquidacion_distribucion_id' => $id,
         'es_distribuido' => 'SI',
         'updated_at' => date("Y-m-d H:i:s")     ]);  
            $i++;

        }

        return response()->json($i, "201");

    }

       
    
}
