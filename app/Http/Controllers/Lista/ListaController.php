<?php
namespace App\Http\Controllers\Lista;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Support\Facades\DB; 


use App\Http\Controllers\Controller;

class ListaController extends ApiController
{
  

    public function getListaCirugia()
    {
             $horario = DB::select( DB::raw("SELECT id, descripcion , created_at, updated_at FROM lista_cirugia"));
        return response()->json($horario, "201");
    }

    public function ActualizarListaCirugia(Request $request){
    
       $res =  DB::table('lista_cirugia')    
        ->update([
        'id' => $id,
        'descripcion' => $descripcion,
        'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) 
        ]);
       //return $res;
         return $this->showOne($res);
    }

    public function CrearListaCirugia(Request $request){                
        $paciente_id= DB::table('lista_cirugia')->insertGetId([
            'descripcion'=> $request->descripcion,            
            'created_at' => date("Y-m-d H:i:s", strtotime('-3 hours')),
            'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours'))    
        ]);
        return response()->json($paciente_id, "201");
        }   



        

        public function getListaEstudios()
        {
                 $horario = DB::select( DB::raw("SELECT id, descripcion , created_at, updated_at FROM lista_estudios"));
            return response()->json($horario, "201");
        }
    
        public function ActualizarListaEstudios(Request $request){
          
           $res =  DB::table('lista_estudios')    
           ->where('id', $id) ->limit(1)  
            ->update([
            'descripcion' => $descripcion,
            'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) 
            ]);
            return response()->json($res, "201");
            // return $this->showOne($res);
        }
    
        public function CrearListaEstudios(Request $request){                
            $paciente_id= DB::table('lista_estudios')->insertGetId([
                'descripcion'=> $request->descripcion,            
                'created_at' => date("Y-m-d H:i:s", strtotime('-3 hours')),
                'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours'))    
            ]);
            return response()->json($paciente_id, "201");
            }  


            
            public function getListaReceta()
            {
                     $horario = DB::select( DB::raw("SELECT id, descripcion , created_at, updated_at FROM lista_receta"));
                return response()->json($horario, "201");
            }
        
            public function ActualizarListaReceta(Request $request){
              
               $res =  DB::table('lista_receta')   
               ->where('id', $id) ->limit(1)   
                ->update([
                'descripcion' => $descripcion,
                'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours')) 
                ]);
               //return $res;
                 return $this->showOne($res);
            }
        
            public function CrearListaReceta(Request $request){                
                $paciente_id= DB::table('lista_receta')->insertGetId([
                    'descripcion'=> $request->descripcion,            
                    'created_at' => date("Y-m-d H:i:s", strtotime('-3 hours')),
                    'updated_at' => date("Y-m-d H:i:s", strtotime('-3 hours'))    
                ]);
                return response()->json($paciente_id, "201");
                }  



                
            
            public function getListaObraSocial()
            {
                     $horario = DB::select( DB::raw("SELECT id, descripcion , url FROM lista_obra_social_autorizacion"));
                return response()->json($horario, "201");
            }
        
            public function ActualizarListObraSocial(Request $request){
              
               $res =  DB::table('lista_obra_social_autorizacion')   
               ->where('id', $id) ->limit(1)  
                ->update([
                'descripcion' => $descripcion,
                'url' => $url,
                ]);
               //return $res;
                 return $this->showOne($res);
            }
        
            public function CrearListaObraSocial(Request $request){                
                $paciente_id= DB::table('lista_obra_social_autorizacion')->insertGetId([
                    'descripcion'=> $request->descripcion,            
                    'url'=> $request->url, 
                ]);
                return response()->json($paciente_id, "201");
                }  
}
