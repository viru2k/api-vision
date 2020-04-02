<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 

class ChatController extends ApiController
{
    
/* -------------------------------------------------------------------------- */
/*   CREO EL LISTADO PARA UN USUARIO , DE UN SOLO USO Y NO SE VUELVE A USAR   */
/* -------------------------------------------------------------------------- */

  public function altaUsuarioSesionLista(Request $request){

  $id = $request->input('id');

        $usuarios = DB::select( DB::raw(" SELECT id FROM users WHERE id != :id AND es_activo = 'SI'"
          )
          , array(
          'id' => $id   
        ));

          foreach($usuarios as $usu){
          // DATOS DE LOS OTROS USUARIOS
             $sesion= DB::table('chat_sesion')->insertGetId([
              'grupo_nombre' => 'LISTADO',
              'fecha_modificacion' => date("Y-m-d H:i:s")    
            ]);

            $sesion_usuario= DB::table('chat_sesion_usuario')->insertGetId([
            'sesion_id' => $sesion,
            'usuario_id' => $usu->id,
            'fecha_creacion' => date("Y-m-d H:i:s")    
           ]); 

           // DATOS DEL USUARIO  QUE SOLICITA
           $sesion_usuario= DB::table('chat_sesion_usuario')->insertGetId([
            'sesion_id' => $sesion,
            'usuario_id' => $id,
            'fecha_creacion' => date("Y-m-d H:i:s")    
           ]); 

          }
          return response()->json("USUARIO AGREGADO", 201);
        }


/* -------------------------------------------------------------------------- */
/*                      CREO LA SESION PARA DOS USUARIOS                      */
/* -------------------------------------------------------------------------- */

public function crearSesionListado(Request $request)
  {

  $id = $request->input('id');
  $usuario_id = $request->input('usuario_chat_id');


        $usuarios = DB::select( DB::raw("SELECT id, sesion_id,usuario_id,fecha_creacion, IF(COUNT(sesion_id)>2, 'EXISTE', 'NO_EXISTE') as existe  FROM chat_sesion_usuario WHERE  usuario_id IN(:id, :usuario_id) HAVING sesion_id"
          )
          , array(
          'id' => $id,
          'usuario_id' => $usuario_id      
        ));

     
     if($usuarios[0]->existe==='NO_EXISTE'){

      $sesion= DB::table('chat_sesion')->insertGetId([
        'grupo_nombre' => 'LISTADO',
        'fecha_modificacion' => date("Y-m-d H:i:s")    
      ]);

      $sesion_usuario= DB::table('chat_sesion_usuario')->insertGetId([
      'sesion_id' => $sesion,
      'usuario_id' => $id,
      'fecha_creacion' => date("Y-m-d H:i:s")    
     ]); 

     // DATOS DEL USUARIO  QUE SOLICITA
     $sesion_usuario= DB::table('chat_sesion_usuario')->insertGetId([
      'sesion_id' => $sesion,
      'usuario_id' => $usuario_id,
      'fecha_creacion' => date("Y-m-d H:i:s")    
     ]); 

     }
     return response()->json('SESION CREADA', 201);
        }


/* -------------------------------------------------------------------------- */
/*                  CREO EL LISTADO DE USUARIOS PARA UN GRUPO                 */
/* -------------------------------------------------------------------------- */



public function crearSesionListadoGrupo(Request $request)
  {
    $grupo_nombre = $request->input('grupo_nombre');
    $sesion= DB::table('chat_sesion')->insertGetId([
      'grupo_nombre' => $grupo_nombre,
      'fecha_modificacion' => date("Y-m-d H:i:s")    
    ]);

     
     return response()->json($sesion, 201);
        }


/* -------------------------------------------------------------------------- */
/*                        ASOCIAR UN USUARIO A UN GRUPO                       */
/* -------------------------------------------------------------------------- */

public function asociarUsuarioGrupo(Request $request)
  {
    $sesion_id = $request->input('sesion_id');
    $usuario_id = $request->input('usuario_id');
    $usuario_carga = $request->input('usuario_carga');
    $sesion_id = $request->input('sesion_id');
    $grupo = $request->input('grupo');
    $existe = false;
    $res = DB::select( DB::raw("SELECT  chat_sesion.id as chat_sesion_id, chat_sesion_usuario.id as chat_sesion_usuario_id,  users.id, nombreyapellido , chat_sesion.fecha_modificacion,  chat_sesion_usuario.estado, chat_sesion_usuario.fecha_lectura, grupo_nombre
    FROM  chat_sesion,  chat_sesion_usuario, users
    WHERE chat_sesion.id = chat_sesion_usuario.sesion_id AND  chat_sesion_usuario.usuario_id = users.id AND chat_sesion_usuario.sesion_id   in (SELECT sesion_id FROM  chat_sesion_usuario WHERE chat_sesion_usuario.usuario_id = ".$usuario_id.") AND  chat_sesion_usuario.usuario_id != ".$usuario_id." AND chat_sesion.grupo_nombre ='".$grupo."'
    ")
    , array(    
    'usuario_id' => $usuario_id 
  ));

  
  foreach($res as $t){
    //var_dump ($t);
  
    if($t->id == $usuario_carga){
      //echo 'existe';
      $existe = true;
      $respuesta = 'existente';
    }
  }


/* -------------------------------------------------------------------------- */
/*   SI ES DE LISTADO PRIVADO CREO GRUPO LISTADO Y ASOCIO  LOS DOS USUARIOS   */
/* -------------------------------------------------------------------------- */

/* -------------------------------------------------------------------------- */
/*          OBTENGO EL VALOR PROVEIDO POR EL GRUPO CREADO PREVIAMENTE         */
/* -------------------------------------------------------------------------- */
   


  if(!$existe){
    if($grupo === 'LISTADO'){
      $sesion= DB::table('chat_sesion')->insertGetId([
        'grupo_nombre' => $grupo,
        'fecha_modificacion' => date("Y-m-d H:i:s")    
      ]);
    
    $sesion_usuario= DB::table('chat_sesion_usuario')->insertGetId([
      'sesion_id' => $sesion,
      'usuario_id' => $usuario_carga,
      'estado' => 'NUEVO',
      'fecha_creacion' => date("Y-m-d H:i:s"),
      'fecha_lectura' => date("Y-m-d H:i:s")   
     ]); 
   
        $sesion_usuario= DB::table('chat_sesion_usuario')->insertGetId([
          'sesion_id' => $sesion,
          'usuario_id' => $usuario_id,
          'estado' => 'NUEVO',
          'fecha_creacion' => date("Y-m-d H:i:s"),
          'fecha_lectura' => date("Y-m-d H:i:s")   
         ]); 
      }

      if($grupo !== 'LISTADO'){
        $sesion= DB::table('chat_sesion')->insertGetId([
          'grupo_nombre' => $grupo,
          'fecha_modificacion' => date("Y-m-d H:i:s")    
        ]);
      
      $sesion_usuario= DB::table('chat_sesion_usuario')->insertGetId([
        'sesion_id' => $sesion_id,
        'usuario_id' => $usuario_carga,
        'estado' => 'NUEVO',
        'fecha_creacion' => date("Y-m-d H:i:s"),
        'fecha_lectura' => date("Y-m-d H:i:s")   
       ]); 

      }
    
      $respuesta = 'creado';
  }
 
     return response()->json($respuesta, 201);
        }

/* -------------------------------------------------------------------------- */
/*                         OBTENGO LOS GRUPOS CREADOS                         */
/* -------------------------------------------------------------------------- */



public function getGrupos(Request $request)
{
    $id = $request->input('id');
    $res = DB::select( DB::raw("SELECT * FROM `chat_sesion` WHERE grupo_nombre !='LISTADO' GROUP BY grupo_nombre"
    )
   );
  return response()->json($res, 201);
}

    
/* -------------------------------------------------------------------------- */
/*                       OBTENGO EL LISTADO DE USUARIOS                       */
/* -------------------------------------------------------------------------- */

    public function getSesionListByUsuario(Request $request)
    {

        $id = $request->input('id');
/* SELECT  chat_sesion.id as chat_sesion_id, chat_sesion_usuario.id as chat_sesion_usuario_id,  users.id, nombreyapellido , chat_sesion.fecha_modificacion,  chat_sesion_usuario.estado, chat_sesion_usuario.fecha_lectura, grupo_nombre
        FROM  chat_sesion,  chat_sesion_usuario, users
        WHERE chat_sesion.id = chat_sesion_usuario.sesion_id AND  chat_sesion_usuario.usuario_id = users.id AND chat_sesion_usuario.sesion_id   in (SELECT sesion_id FROM  chat_sesion_usuario WHERE chat_sesion_usuario.usuario_id = ".$id.") AND  chat_sesion_usuario.usuario_id != ".$id." AND chat_sesion.grupo_nombre ='LISTADO'" */
        $res = DB::select( DB::raw("
        SELECT * FROM ((SELECT  chat_sesion.id as chat_sesion_id, chat_sesion_usuario.id as chat_sesion_usuario_id,  users.id, nombreyapellido , chat_sesion.fecha_modificacion,  chat_sesion_usuario.estado, chat_sesion_usuario.fecha_lectura, grupo_nombre
        FROM  chat_sesion,  chat_sesion_usuario, users
        WHERE chat_sesion.id = chat_sesion_usuario.sesion_id AND  chat_sesion_usuario.usuario_id = users.id AND chat_sesion_usuario.sesion_id   in (SELECT sesion_id FROM  chat_sesion_usuario WHERE chat_sesion_usuario.usuario_id = ".$id.") AND  chat_sesion_usuario.usuario_id != ".$id." AND chat_sesion.grupo_nombre ='LISTADO')

UNION

(SELECT  chat_sesion.id as chat_sesion_id, chat_sesion_usuario.id as chat_sesion_usuario_id,  users.id ,grupo_nombre as nombreyapellido, chat_sesion.fecha_modificacion,  chat_sesion_usuario.estado, chat_sesion_usuario.fecha_lectura,  grupo_nombre
        FROM  chat_sesion,  chat_sesion_usuario, users
        WHERE chat_sesion.id = chat_sesion_usuario.sesion_id AND  chat_sesion_usuario.usuario_id = users.id AND chat_sesion_usuario.sesion_id   in (SELECT sesion_id FROM  chat_sesion_usuario WHERE chat_sesion_usuario.usuario_id = ".$id.") AND  chat_sesion_usuario.usuario_id != ".$id." AND chat_sesion.grupo_nombre !='LISTADO' GROUP BY chat_sesion.id)) AS i ORDER BY i.fecha_modificacion DESC
        ")
        , array(
        'id' => $id   
      ));
      return response()->json($res, 201);
    }

/* -------------------------------------------------------------------------- */
/*         OBTENGO EL LISTADO DE GRUPOS A LOS QUE EL USUARIO PERTENECE        */
/* -------------------------------------------------------------------------- */

    public function getChatByUsuario(Request $request)
    {
        $id = $request->input('id');
        $res = DB::select( DB::raw("SELECT  chat_sesion.id as chat_sesion_id, chat_sesion_usuario.id as chat_sesion_usuario_id,  users.id, nombreyapellido , chat_sesion.fecha_modificacion,  chat_sesion_usuario.estado
        FROM  chat_sesion,  chat_sesion_usuario, users
        WHERE chat_sesion.id = chat_sesion_usuario.sesion_id AND  chat_sesion_usuario.usuario_id = users.id AND chat_sesion_usuario.sesion_id   in (SELECT sesion_id FROM  chat_sesion_usuario WHERE chat_sesion_usuario.usuario_id = :id) AND  chat_sesion_usuario.usuario_id != :id"
        )
        , array(
        'id' => $id   
      ));
      return response()->json($res, 201);
    }

/* -------------------------------------------------------------------------- */
/*                         OBTENGO EL CHAT POR SESION                         */
/* -------------------------------------------------------------------------- */

    public function getChatBySesion(Request $request)
    {
      $sesion_id = $request->input('sesion_id');
      $usuario_id = $request->input('id');
      $grupo_nombre = $request->input('grupo_nombre');
      $limite = $request->input('limite');
    //  echo $limite;

        $res = DB::select( DB::raw("SELECT * FROM (SELECT cha_chat.sesion_id, cha_chat.usuario_id, mensaje, cha_chat.fecha_lectura, cha_chat.fecha_creacion, es_adjunto, archivo,  users.nombreyapellido 
        FROM `cha_chat`, chat_sesion, users 
        WHERE  cha_chat.sesion_id = chat_sesion.id  AND cha_chat.usuario_id = users.id AND chat_sesion.id = :sesion_id   ORDER BY  fecha_creacion DESC  ".$limite." ) AS temp  ORDER BY  fecha_creacion ASC
        ")
        , array(
        'sesion_id' => $sesion_id    
      ));
      if($grupo_nombre ==='LISTADO'){
      $update = DB::table('chat_sesion_usuario') 
     ->where('sesion_id', '=',  $sesion_id)
     ->where('usuario_id', '=',  $usuario_id)
     ->update( [ 
      'estado' => 'LEIDO',
      'fecha_lectura' => date("Y-m-d H:i:s")       
      ]);

      $update = DB::table('chat_sesion') 
      ->where('id', '=',  $sesion_id)      
      ->update( [        
       'fecha_modificacion' => date("Y-m-d H:i:s")
       ]);
       
     }else{ 

      $update = DB::table('chat_sesion') 
      ->where('id', '=',  $sesion_id)      
      ->update( [        
       'fecha_modificacion' => date("Y-m-d H:i:s")
       ]);
       $update = DB::table('chat_sesion_usuario') 
       ->where('sesion_id', '=',  $sesion_id)
       ->where('usuario_id', '=',  $usuario_id)
       ->update( [ 
        'estado' => 'LEIDO',
        'fecha_lectura' => date("Y-m-d H:i:s")       
        ]);
       
     }
   
 

     return response()->json($res, 201);
    }

/* -------------------------------------------------------------------------- */
/*                         INSERTO UN RENGLON AL CHAT  Y ACTUALIZO EL ESTADO DEL LISTADO   */
/* -------------------------------------------------------------------------- */

public function insertarRenglonChat(Request $request)
  {
    $sesion_usuario= DB::table('cha_chat')->insertGetId([
      'sesion_id' => $request->sesion_id,
      'usuario_id' => $request->usuario_id,
      'estado' => 'PENDIENTE',
      'mensaje' => $request->mensaje,
      'fecha_creacion' => date("Y-m-d H:i:s"),
      'es_adjunto' => $request->es_adjunto,
      'archivo' => $request->archivo
      
     ]); 

     $update = DB::table('chat_sesion_usuario') 
     ->where('sesion_id', '=',  $request->sesion_id)
     ->where('usuario_id', '=',  $request->usuario_id)
     ->update( [ 
      'estado' => 'NUEVO',
      'fecha_lectura' => date("Y-m-d H:i:s"),       
    ]);  
     
     return response()->json($sesion_usuario, 201);
        }

/* -------------------------------------------------------------------------- */
/*                   ACTUALIZO EL CHAT Y OBTENGO EL LISTADO                   */
/* -------------------------------------------------------------------------- */

public function actualizarRenglonListado(Request $request)
  {
    $sesion_id = $request->input('sesion_id');
    $usuario_id = $request->input('usuario_id');



       $update = DB::table('chat_sesion_usuario') 
       ->where('sesion_id',  $sesion_id)
       ->where('usuario_id', '=',  $usuario_id)
       ->update( [ 
        'estado' => 'LEIDO',
        'fecha_lectura' => date("Y-m-d H:i:s"),       
      ]);  

      $update = DB::table('cha_chat') 
      ->where('sesion_id',  $sesion_id)
      ->where('usuario_id', '<>',  $usuario_id)
      ->update( [ 
       'estado' => 'LEIDO',    
       'fecha_lectura' => date("Y-m-d H:i:s"),    
     ]);  
     
     DB::select( DB::raw("SELECT chat_sesion.id, usuario_id, fecha_creacion, chat_sesion.grupo_nombre, chat_sesion.fecha_modificacion, users.nombreyapellido  
     FROM chat_sesion_usuario, chat_sesion, users 
     WHERE chat_sesion_usuario.sesion_id = chat_sesion.id AND chat_sesion_usuario.usuario_id = users.id AND chat_sesion.id = (SELECT id FROM chat_sesion_usuario WHERE chat_sesion_usuario.usuario_id = :id)"
     )
     , array(
     'id' => $id   
   ));
     return response()->json($res, 201);
        }

  

        public function showUploadFile(Request $request) {


          $parts = explode('/', $request->url());
         // var_dump($parts);
          $sesion_id =  $parts[8];
          $usuario_id =  $parts[9];
        //  echo $usuario_id;
           
        
          $fecha = date("Y-m-d-H-i-s");
          $allowedfileExtension=['pdf','jpg','png','docx','pdf'];
          $files = $request->file('images');
          foreach($files as $file){
          $filename = $file->getClientOriginalName();
          $extension = $file->getClientOriginalExtension();
          $check=in_array($extension,$allowedfileExtension);
          $parts = explode('/', $request->url());
           $last = end($parts);
          $destinationPath = 'uploads/chat/'.$last.'-'.$fecha;
          $without_extension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
          

          $sesion_usuario= DB::table('cha_chat')->insertGetId([
            'sesion_id' => $sesion_id,
            'usuario_id' => $usuario_id,
            'estado' => 'PENDIENTE',
            'mensaje' =>  $file->getClientOriginalName(),
            'fecha_creacion' => date("Y-m-d H:i:s"),
            'es_adjunto' => 'SI',
            'archivo' => $destinationPath.'/'.$file->getClientOriginalName()
            
           ]); 
      
           $update = DB::table('chat_sesion_usuario') 
           ->where('sesion_id', '=',  $sesion_id)
           ->where('usuario_id', '=',  $usuario_id)
           ->update( [ 
            'estado' => 'NUEVO',
            'fecha_lectura' => date("Y-m-d H:i:s"),       
          ]);  
          
          
          $file->move($destinationPath,$filename);
          
          } 
          
                  return response()->json("Upload Successfully ", 201);
               }
}

