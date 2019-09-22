<?php

namespace App\Http\Controllers\Articulo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

 
   

    public function getArticulosTipo()
    {
        $res = DB::select( DB::raw("
        SELECT id, tipo_articulo FROM articulo_tipo
    "));         
    return response()->json($res, 201);

    }

    public function getComprobantes()
    {
        $res = DB::select( DB::raw("
        SELECT articulo.id, articulo.nombre, codigo, tipo_articulo_id, articulo.precio as articulo_precio, estado_id, articulo_tipo.tipo_articulo,   articulo_estado.estado, comprobante.id AS comprobante_id,
        comprobante.numero, comprobante.fecha_alta, articulo_movimiento.precio as articulo_movimiento_precio ,articulo_movimiento.id as articulo_movimiento_id , articulo_movimiento.bonificacion as  articulo_movimiento_bonificacion,
         articulo_movimiento.sucursal_id, articulo_movimiento.comprobante_id, comprobante.total_facturado, comprobante.iva, comprobante.total_facturado_iva , comprobante_tipo.tipo
        FROM articulo, articulo_tipo, articulo_estado, comprobante, articulo_movimiento , comprobante_tipo
        WHERE articulo.tipo_articulo_id = articulo_tipo.id AND articulo.estado_id = articulo_estado.id  AND articulo_movimiento.comprobante_id = comprobante.id AND articulo.id = articulo_movimiento.articulo_id AND comprobante_tipo.id = comprobante.comprobante_tipo_id    "));         
    return response()->json($res, 201);

    }

    public function getArticulosActivo()
        {
            $res = DB::select( DB::raw("
            SELECT articulo.id, articulo.nombre, codigo, tipo_articulo_id, precio, estado_id, articulo_tipo.tipo_articulo,   articulo_estado.estado FROM articulo, articulo_tipo, articulo_estado WHERE articulo.tipo_articulo_id = articulo_tipo.id AND articulo.estado_id = articulo_estado.id AND articulo_estado.estado = 'EN STOCK'
        "));         
        return response()->json($res, 201);

        }

        public function getArticuloTipo()
        {
            $res = DB::select( DB::raw("
            SELECT id, tipo_articulo FROM articulo_tipo 
        "));         
        return response()->json($res, 201);

        }

        
        public function setArticuloTipo(Request $request)
        {

        $resp= DB::table('articulo_tipo')->insertGetId([
                    
            'tipo_articulo' => $request["tipo_articulo"] 
        ]);    
       return response()->json($resp, 201);    
}


public function actualizarArticuloTipo(Request $request ,$id)
{       

   $res = DB::table('articulo_tipo')         
   ->where('id',$request->id ) ->limit(1) 
   ->update( [            
    'tipo_articulo' =>$request->tipo_articulo,
    ]); 
 
  return response()->json($res, 201);       
//  echo $request;
}
      

        public function getMovimiento()
        {
            $res = DB::select( DB::raw("
            SELECT articulo.id, articulo.nombre, codigo, tipo_articulo_id, articulo.precio as articulo_precio, estado_id, articulo_tipo.tipo_articulo,   articulo_estado.estado, comprobante.id AS comprobante_id, comprobante.numero, comprobante.fecha_alta, articulo_movimiento.precio as articulo_movimiento_precio ,articulo_movimiento.id as articulo_movimiento_id , articulo_movimiento.bonificacion as  articulo_movimiento_bonificacion, articulo_movimiento.sucursal_id, articulo_movimiento.comprobante_id, comprobante.total_facturado, comprobante.iva, comprobante.total_facturado_iva FROM articulo, articulo_tipo, articulo_estado, comprobante, articulo_movimiento WHERE articulo.tipo_articulo_id = articulo_tipo.id AND articulo.estado_id = articulo_estado.id  AND articulo_movimiento.comprobante_id = comprobante.id AND articulo.id = articulo_movimiento.articulo_id
        "));         
        return response()->json($res, 201);

        }    

        public function getComprobanteTipo()
        {
            $res = DB::select( DB::raw("
            SELECT id, ultimo_numero, proximo_numero, tipo, estado, updated_at FROM comprobante_tipo
        "));         
        return response()->json($res, 201);

        }   


        public function crearComprobante(Request $request){
            $t =$request;
            $tmp_fecha = str_replace('/', '-', $request->fecha_alta);
            $fecha_alta =  date('Y-m-d H:i', strtotime($tmp_fecha));  

           // echo $request->fecha_alta;
        //    $someArray = json_decode($t["AgendaDiaBloqueo"]);

        $comprobante_id =    DB::table('comprobante')->insertGetId(
           ['fecha_alta' => $fecha_alta,
            'fecha_baja' => '2099-12-31 00:00:00',
            'numero' => $request->numero,
            'total_facturado' => $request->total_facturado,
            'iva' => $request->iva,
            'total_facturado_iva' => $request->total_facturado_iva,
            'estado' => $request->estado,
            'usuario_alta_id' => $request->usuario_alta_id,
            'comprobante_tipo_id' => $request->comprobante_tipo_id
          
           ]           
        );   
        //var_dump($request->articuloMovimiento[0]['total_facturado']);
      //  echo $request->articuloMovimiento[0]['total_facturado'];
       
            $i = 0;
            while(isset($request->articuloMovimiento[$i])){

             //   var_dump($request[$i]);
             $articulo_movimiento_id =    DB::table('articulo_movimiento')->insertGetId(
                [                
                 'articulo_id' => $request->articuloMovimiento[$i]["articulo_id"],
                 'fecha_alta' => $fecha_alta,
                 'fecha_baja' => '2099-12-31 00:00:00',
                 'precio' => $request->articuloMovimiento[$i]["precio"],
                 'cantidad' => $request->articuloMovimiento[$i]["cantidad"],
                 'total_facturado' => $request->articuloMovimiento[$i]["total_facturado"],
                 'bonificacion' => $request->articuloMovimiento[$i]["bonificacion"],
                 'estado' => $request->articuloMovimiento[$i]["estado"],
                 'sucursal_id' => $request->articuloMovimiento[$i]["sucursal_id"],
                 'comprobante_id' => $request->comprobante_tipo_id,
                 'proveedor_id' => 1,                 
                 'created_at' => date("Y-m-d H:i:s", strtotime('-3 hours')),
                 'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours'))
                 ]           
                );   
                $i++;
            }

            $proximo = $request->numero;
            $proximo ++;
            
            $id = DB::table('comprobante_tipo') 
            ->where('id', $request->comprobante_tipo_id) ->limit(1) 
            ->update( [     
             'ultimo_numero' => $request->numero,
             'proximo_numero' => $proximo,
             'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours'))
             	  ]); 
            $res = DB::select( DB::raw("
            SELECT id, ultimo_numero, proximo_numero, tipo, estado, updated_at FROM comprobante_tipo where id = ".$request->comprobante_tipo_id."
                   "));     
                

            return response()->json($res, "201"); 
        }



        public function anularComprobante(){
            $id =$request->input('comprobante_id');
            $fecha_baja =$request->input('fecha_baja');
            $tmp_fecha = str_replace('/', '-', $fecha_baja);
            $fecha_baja =  date('Y-m-d H:i', strtotime($tmp_fecha)); 

            $comprobante_id = DB::table('comprobante') 
            ->where('id', $id) ->limit(1) 
            ->update( [     
                'fecha_baja' => $fecha_baja,
                'estado' => 'ANULADO',
                'usuario_alta_id' => $request->usuario_alta_id,
                   ]); 
                   
           $id = DB::table('articulo_movimiento') 
           ->where('comprobante_id', $comprobante_id) ->limit(1) 
           ->update( [     
               'fecha_baja' => $fecha_baja,
               'estado' => 'ANULADO',               
                  ]); 

        }
    

        public function getMovimientoByComprobanteNro(Request $request)
        {
            $id =$request->input('id');
            $res = DB::select( DB::raw("
            SELECT articulo.id, articulo.nombre, codigo, tipo_articulo_id, articulo.precio as articulo_precio, estado_id, articulo_tipo.tipo_articulo,   articulo_estado.estado, comprobante.id AS comprobante_id,
            comprobante.numero, comprobante.fecha_alta, articulo_movimiento.precio as articulo_movimiento_precio ,articulo_movimiento.id as articulo_movimiento_id , articulo_movimiento.bonificacion as  articulo_movimiento_bonificacion,
             articulo_movimiento.sucursal_id, articulo_movimiento.comprobante_id, comprobante.total_facturado, comprobante.iva, comprobante.total_facturado_iva , comprobante_tipo.tipo
            FROM articulo, articulo_tipo, articulo_estado, comprobante, articulo_movimiento , comprobante_tipo
            WHERE articulo.tipo_articulo_id = articulo_tipo.id AND articulo.estado_id = articulo_estado.id  AND articulo_movimiento.comprobante_id = comprobante.id AND articulo.id = articulo_movimiento.articulo_id AND comprobante_tipo.id = comprobante.comprobante_tipo_id  AND comprobante.id = ".$id."
        "));         
        return response()->json($res, 201);
        }  
        
        public function getMovimientoByComprobanteFecha(Request $request)
        {    
            $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
            $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha));               
            $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
            $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));   
            $res = DB::select( DB::raw("
            SELECT articulo.id, articulo.nombre, codigo, tipo_articulo_id, articulo.precio as articulo_precio, estado_id, articulo_tipo.tipo_articulo,   articulo_estado.estado, comprobante.id AS comprobante_id,
            comprobante.numero, comprobante.fecha_alta, articulo_movimiento.precio as articulo_movimiento_precio ,articulo_movimiento.id as articulo_movimiento_id , articulo_movimiento.bonificacion as  articulo_movimiento_bonificacion,
             articulo_movimiento.sucursal_id, articulo_movimiento.comprobante_id, comprobante.total_facturado, comprobante.iva, comprobante.total_facturado_iva , comprobante_tipo.tipo
            FROM articulo, articulo_tipo, articulo_estado, comprobante, articulo_movimiento , comprobante_tipo
            WHERE articulo.tipo_articulo_id = articulo_tipo.id AND articulo.estado_id = articulo_estado.id  AND articulo_movimiento.comprobante_id = comprobante.id AND articulo.id = articulo_movimiento.articulo_id AND comprobante_tipo.id = comprobante.comprobante_tipo_id AND fecha_alta BETWEEN ".$fecha_desde." AND ".$fecha_hasta."
        "));         
        return response()->json($res, 201);
        }   


        public function getArticulos()
        {
            $res = DB::select( DB::raw("
            SELECT articulo.id, articulo.nombre, codigo, tipo_articulo_id, precio, estado_id, articulo_tipo.tipo_articulo,articulo_tipo.id as tipo_articulo_id,   articulo_estado.estado FROM articulo, articulo_tipo, articulo_estado WHERE articulo.tipo_articulo_id = articulo_tipo.id AND articulo.estado_id = articulo_estado.id 
        "));         
        return response()->json($res, 201);

        }

        public function setArticulo(Request $request)
        {

        $resp= DB::table('articulo')->insertGetId([
            'nombre' => $request["nombre"],
            'codigo' => $request["codigo"],            
            'tipo_articulo_id' => $request["tipo_articulo_id"],
            'precio' => $request["precio"],
            'estado_id' =>  $request["estado_id"],
            'created_at' => date("Y-m-d H:i:s", strtotime('-3 hours')),
            'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours'))    
        ]);    
       return response()->json($resp, 201);    
}


public function actualizarArticulo(Request $request ,$id)
{       
   $tmp_fecha = str_replace('/', '-', $request->fecha_alta);
   $fecha_alta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));         
    
   $res = DB::table('articulo')         
   ->where('id',$request->id ) ->limit(1) 
   ->update( [            
    'nombre' =>$request->nombre,
    'codigo' =>$request->codigo,   
    'precio' =>$request->precio,   
    'tipo_articulo_id' =>$request->tipo_articulo_id,
    'estado_id' =>$request->estado_id,    
    'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) ]); 
 
  return response()->json($res, 201);       
//  echo $request;
}



/*
public function crearComprobante(Request $request)
{
    // CREO EL COMPROBANTE
    $tmp_fecha = str_replace('/', '-', $request->fecha_alta);
    $fecha_alta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));         
     
    $id= DB::table('comprobante')->insertGetId([
        'numero' => $request->numero,
        'total_facturado' => $request->total_facturado,
        'iva' => $request->iva,
        'total_facturado_iva' => $request->total_facturado_iva,
        'fecha_alta' => $fecha_alta,        
        'estado' => $request->estado,
        'created_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) ,
        'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) 
    ]);
    $t_usuarios = $request->notificacion_usuario;

    // AGREGO LOS RENGLONES
    foreach ($t_usuarios as $res) {
     
        //  var_dump($res["valor_original"]);
          DB::table('articulo_movimiento')->insertGetId([            
            'comprobante_id' => $id,  
            'articulo_id' => $res["articulo_id"],   
            'fecha_alta' => $res["fecha_alta"],   
            'fecha_baja' => "2099-12-31 00:00:00",   
            'precio' => $res["precio"],   
            'bonificacion' => $res["bonificacion"],   
            'estado' => $res["estado"],   
            'sucursal_id' => $res["sucursal_id"],                            
            'created_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) ,
            'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) 
          ]);      
   
      } 
    //ACTUALIZO EL COMPROBANTE
      $update = DB::table('comprobante')         
        ->where('id',$request->id ) ->limit(1) 
        ->update( [            
            'numero' =>$request->numero,
       'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) ]); 

    return response()->json($id, 201);      
     }
*/

     public function actualizarComprobante(Request $request ,$id)
     {       
        $tmp_fecha = str_replace('/', '-', $request->fecha_alta);
        $fecha_alta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));         
         
        $update = DB::table('comprobante')         
        ->where('id',$request->id ) ->limit(1) 
        ->update( [            
         'numero' =>$request->numero,
         'total_facturado' =>$request->total_facturado,
         'iva' =>$request->iva,
         'total_facturado_iva' =>$request->total_facturado_iva,
         'fecha_alta' =>$request->fecha_alta,
         'estado' =>$request->estado,
         'fecha_alta' =>$fecha_alta,
         'usuario_alta_id' =>$usuario_alta_id,
         'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) ]); 
      
       return response()->json($res, 201);       
     //  echo $request;
     }

     
     public function actualizarMovimientoComprobante(Request $request ,$id)
     {       
         $notificacion_id =$request['id'];         
        $fecha = date("Y-m-d H:i:s");
        $update = DB::table('articulo_movimiento')         
        ->where('id',$request->id ) ->limit(1) 
        ->update( [            
         'numero' =>$request->numero,
         'total_facturado' =>$request->total_facturado,
         'iva' =>$request->iva,
         'total_facturado_iva' =>$request->total_facturado_iva,
         'fecha_alta' =>$request->fecha_alta,
         'estado' =>$request->estado,
         'fecha_alta' =>$fecha_alta,
         'usuario_alta_id' =>$usuario_alta_id,
         'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) ]); 
      
       return response()->json($res, 201);       
     //  echo $request;
     }
}
