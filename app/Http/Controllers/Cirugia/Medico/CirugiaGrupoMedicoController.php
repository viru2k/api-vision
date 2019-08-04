<?php

namespace App\Http\Controllers\Cirugia\Medico;

use App\models\CirugiaGrupoMedico;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class CirugiaGrupoMedicoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
   
      $res= DB::table('cirugia_grupo_medico','cirugia_grupo','medicos')
            ->join('medicos', 'medicos.id', '=', 'cirugia_grupo_medico.medico_id')
            ->join('cirugia_grupo', 'cirugia_grupo.id', '=', 'cirugia_grupo_medico.grupo_id')
            ->select(
                'cirugia_grupo_medico.id',
                'medicos.id as medico_id',                
                'cirugia_grupo_medico.codigo',
                'cirugia_grupo_medico.porcentaje_distribucion',
                'medicos.nombre',
                'medicos.apellido',
                'cirugia_grupo.nombre as cirugia_grupo_nombre', 
                'cirugia_grupo.id as id_grupo'
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
            'porcentaje_distribucion' => 'required',          
            'codigo' => 'required',          
            'medico_id' => 'required',  
            'grupo_id' => 'required',  
        ];

        $this->validate($request, $rules);
        $resp = cirugiaGrupoMedico::create($request->all());
        return $this->showOne($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\CirugiaGrupoMedico  $cirugiaGrupoMedico
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
      $res= DB::table('cirugia_grupo_medico','cirugia_grupo')
       ->join('medicos', 'medicos.id', '=', 'cirugia_grupo_medico.medico_id')
       ->join('cirugia_grupo', 'cirugia_grupo.id', '=', 'cirugia_grupo_medico.grupo_id')
       ->select(
        'cirugia_grupo_medico.id',
        'medicos.id as medico_id',                
        'cirugia_grupo_medico.codigo',
        'cirugia_grupo_medico.porcentaje_distribucion',
        'medicos.nombre',
        'medicos.apellido',
        'cirugia_grupo.nombre as cirugia_grupo_nombre', 
        'cirugia_grupo.id as id_grupo'
        )
       ->where('cirugia_grupo_medico.id','=', $id)
       ->get();
   return $this->showAll($res);
   
    }

  
  


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\CirugiaGrupoMedico  $cirugiaGrupoMedico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CirugiaGrupoMedico $cirugiaGrupoMedico)
    {
        $cirugiaGrupoMedico->fill($request->only([
            'grupo_id',
            'porcentaje_distribucion',
            'medico_id',
            'codigo',
    ]));

   if ($cirugiaGrupoMedico->isClean()) {
        return $this->errorRepsonse('Se debe especificar al menos un valor', 422);
    }
 //  $cirugiaGrupoMedico->save();
 //   return $this->showOne($cirugiaGrupoMedico);
    var_dump($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\CirugiaGrupoMedico  $cirugiaGrupoMedico
     * @return \Illuminate\Http\Response
     */
    public function destroy(CirugiaGrupoMedico $cirugiaGrupoMedico)
    {
        //
    }




    public function  bygrupo($id)
    { 
      $res= DB::table('cirugia_grupo_medico','cirugia_grupo')
       ->join('medicos', 'medicos.id', '=', 'cirugia_grupo_medico.medico_id')
       ->join('cirugia_grupo', 'cirugia_grupo.id', '=', 'cirugia_grupo_medico.grupo_id')
       ->select('cirugia_grupo_medico.codigo','cirugia_grupo_medico.porcentaje_distribucion','medicos.nombre','medicos.apellido','cirugia_grupo.nombre as cirugia_grupo_nombre', 'cirugia_grupo.id as id_grupo')
       ->where('cirugia_grupo.id','=', $id)
       ->get();
   return $this->showAll($res);
    }
}
