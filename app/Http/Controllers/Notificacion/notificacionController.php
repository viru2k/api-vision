<?php

namespace App\Http\Controllers\Notificacion;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class notificacionController extends Controller
{
   
// OBTENGO TODAS  LAS ACCIONES PARA UNA NOTIFICACION (USUARIOS Y FECHA DE LECTURA)
    public function getNotificacionesBynotificacionId(Request $request)
    {               
        $id =$request->input('id');        

        $res = DB::select( DB::raw("SELECT notificacion.id, titulo, mensaje, tipo_mensaje, url,notificacion_usuario.id as notificacion_usuario_id, notificacion_usuario.fecha_lectura,
         notificacion_usuario.usuario_estado, notificacion_usuario.usuario_id, users.nombreyapellido , usuario_creo
        FROM notificacion, notificacion_usuario, users 
        WHERE notificacion.id = notificacion_usuario.notificacion_id AND notificacion_usuario.usuario_id = users.id AND notificacion.id = ".$id." ORDER BY notificacion.created_at DESC 
    "));
       
      return response()->json($res, 201);
    }

// OBTENGO LAS NOTIFICACIONES DONDE SE VE AFECTADO UN USUARIO
    public function getNotificacionesByUsuario(Request $request)
    {               
        $id =$request->input('id');        

        $horario = DB::select( DB::raw("SELECT notificacion.id, titulo, mensaje, tipo_mensaje, url,notificacion_usuario.id as notificacion_usuario_id, notificacion_usuario.fecha_lectura, 
        notificacion_usuario.usuario_estado, notificacion_usuario.usuario_id, users.nombreyapellido , usuario_creo, notificacion.created_at
        FROM notificacion, notificacion_usuario, users 
        WHERE notificacion.id = notificacion_usuario.notificacion_id AND notificacion_usuario.usuario_id = users.id AND users.id = ".$id."
    "));
       
      return response()->json($horario, 201);
    }
   


    

    public function crearNotificacion(Request $request)
{
    $url_archivos = '';
    // SI ES UN ARCHIVO ADJUNTO LO AGREGO
    if($request->tipo_mensaje == 'archivo'){
    
        $fecha = date("Y-m-d-Hi");
        $allowedfileExtension=['pdf','jpg','png','docx','xls','xlsx'];
        $files = $request->file('images');
        foreach($files as $file){
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            $destinationPath = 'uploads/notificaciones/'.$fecha;
            $without_extension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $file->move($destinationPath,$filename);
        }
       $url_archivos =  'uploads/'.$fecha;
    }

    // INSERTO EL MENSAJE
       
     
    $id= DB::table('notificacion')->insertGetId([
        'titulo' => $request->titulo,
        'mensaje' => $request->mensaje,
        'tipo_mensaje' => $request->tipo_mensaje,
        'usuario_creo' => $request->usuario_creo,
        'url' => $url_archivos,        
        'created_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) ,
        'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) 
    ]);
    $t_usuarios = $request->notificacion_usuario;
 //   echo $request->notificacion_usuario[0];
    // UNA VEZ CREADA LA NOTIFICACION AGREGO LOS USUARIOS
    foreach ($t_usuarios as $res) {
     
        //  var_dump($res["valor_original"]);
          DB::table('notificacion_usuario')->insertGetId([            
            'notificacion_id' => $id,  
            'usuario_id' => $res["id"],   
            'usuario_estado' => 'PENDIENTE',
            'fecha_lectura' => '2099-12-31 00:00:00'
          ]);      
   
      } 
    return response()->json($id, 201);      
     }



     public function confirmarNotificacionByUsuario(Request $request ,$id)
     {       
         $notificacion_id =$request['id'];         
        $fecha = date("Y-m-d H:i:s");
        $res = DB::update( DB::raw("
        UPDATE notificacion_usuario SET fecha_lectura =now(),  usuario_estado = 'LEIDO' WHERE notificacion_id = ".$notificacion_id." AND usuario_id = ".$id.""));        
      
       return response()->json($res, 201);       
     //  echo $request;
     }
 
}
