<?php

namespace App\Http\Controllers\Cirugia;

use App\models\CirugiaGrupoMedicoFactura;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CirugiaGrupoMedicoFacturaController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = DB::table('cirugia_grupo_medico_factura')->get();
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
            'factura' => 'required',          
            'cirugia_grupo_medico_id' => 'required',   
        ];

        $this->validate($request, $rules);
        $resp = CirugiaGrupoMedicoFactura::create($request->all());
        return $this->showOne($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\CirugiaGrupoMedicoFactura  $cirugiaGrupoMedicoFactura
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = DB::table('cirugia_grupo_medico_factura')
        ->where('id','=',$id)
        ->get();
        return $this->showAll($res);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\CirugiaGrupoMedicoFactura  $cirugiaGrupoMedicoFactura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CirugiaGrupoMedicoFactura $cirugiaGrupoMedicoFactura)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\CirugiaGrupoMedicoFactura  $cirugiaGrupoMedicoFactura
     * @return \Illuminate\Http\Response
     */
    public function destroy(CirugiaGrupoMedicoFactura $cirugiaGrupoMedicoFactura)
    {
        //
    }
}
