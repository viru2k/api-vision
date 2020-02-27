<?php

namespace App\Http\Controllers\Medico;

use App\models\Medico; 
use App\models\MedicoObraSocial;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class MedicoController extends ApiController
{
    
    
    public function __construct(){
        //$this->middleware('client.credentials');
       
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $factura = DB::select( DB::raw("SELECT id, apellido, nombre, domicilio, fecha_matricula, telefono, telefono_cel, email, email_laboral, cuit, ing_brutos, usuario_id, usuario_old, codigo_old, especialidad_old, categoria_iva_id, factura_documento_comprador_id, punto_vta_id, factura_comprobante_id, factura_key, factura_crt,fecha_alta_afip, es_medico_activo, created_at, updated_at FROM medicos WHERE es_medico_activo = 'SI'
         "));
                return response()->json($factura, 201);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    // echo $request["apellido"];   
        $rules = [
            'nombre' => 'required',
            'apellido' => 'required',            
            'fecha_matricula' => 'required',
        ];
        $this->validate($request, $rules);        
        $request['fecha_matricula'] = date('Y-m-d', strtotime( $request['fecha_matricula'])); 
        $id =    DB::table('medicos')->insertGetId([
            'apellido' => $request["apellido"],
            'nombre' => $request["nombre"],
            'domicilio' => $request["domicilio"],
            'fecha_matricula' => $request["fecha_matricula"],
            'telefono' => $request["telefono"],
            'telefono_cel' => $request["telefono_cel"],
            'email' => $request["email"],
            'email_laboral' => $request["email_laboral"],
            'cuit' => $request["cuit"],
            'ing_brutos' => $request["ing_brutos"],
            'usuario_id' => $request["usuario_id"],
            'codigo_old' => 1,
            'especialidad_old' => 1,
            'factura_key' => "-",
            'usuario_old' => 1,
            'especialidad_old' => 1,
            'categoria_iva_id' => $request['categoria_iva_id'],          
            'factura_documento_comprador_id' => $request['factura_documento_comprador_id'],    
            'punto_vta_id' => $request['punto_vta_id'],    
            'factura_comprobante_id' => $request['factura_comprobante_id'],   
             'fecha_alta_afip' => $request["fecha_alta_afip"],
             'factura_key' => $request["factura_key"],
             'factura_crt' => $request["factura_crt"],
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")              
            ]);   
           $medico= Medico::findOrFail($id);
        return $this->showOne($medico, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Medico  $medico
     * @return \Illuminate\Http\Response
     */
    public function show(Medico $medico)
    {

        
        return $this->showOne($medico);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Medico  $medico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //echo $request['email_laboral'];
        $medico = medico::findOrFail($id);     
        $request['fecha_matricula'] = date('Y-m-d', strtotime($request['fecha_matricula'])); 
       //$medico->save();

       $update = DB::table('medicos') 
        ->where('id', $id) ->limit(1) 
        ->update( [ 
         'nombre' => $request['nombre'],
         'apellido' => $request['apellido'],
         'cuit' => $request['cuit'],
         'domicilio' => $request['domicilio'],
         'email' => $request['email'],
         'email_laboral' => $request['email_laboral'],
         'fecha_matricula' => $request['fecha_matricula'],
         'ing_brutos' => $request['ing_brutos'],
         'telefono' => $request['telefono'],
         'telefono_cel' => $request['telefono_cel'],    
         'categoria_iva_id' => $request['categoria_iva_id'],          
         'factura_documento_comprador_id' => $request['factura_documento_comprador_id'],    
         'punto_vta_id' => $request['punto_vta_id'],    
         'factura_comprobante_id' => $request['factura_comprobante_id'],    
         'fecha_alta_afip' => $request['fecha_alta_afip'],
         'factura_key' => $request['factura_key'],
         'factura_crt' => $request['factura_crt'],
         'updated_at' => date("Y-m-d H:i:s")     ]); 
         $medico = medico::findOrFail($id);
        return $this->showOne($medico);

    }

    public function postMedicoObraSocial(Request $request)
    {
        
        $rules = [
            'medico_id' => 'required',
            'obra_social_id' => 'required',                        
        ];

        $this->validate($request, $rules);        
        //$medico = Medico::create($request->all());
        $id =    DB::table('medicos_os')->insertGetId([
            'medico_id' => $request["medico_id"],
             'obra_social_id' => $request["obra_social_id"],
             'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")   
            ]);   
           $medico= MedicoObraSocial::findOrFail($id);
        return $this->showOne($medico, 201);

    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Medico  $medico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medico $medico)
    {
        //
    }


    public function getAfip()
    {
        $afip = new Afip(array('CUIT' => 20111111112));
        return response()->json($afip, 201);
    }
}
