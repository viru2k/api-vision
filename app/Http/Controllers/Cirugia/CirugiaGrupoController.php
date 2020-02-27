<?php

namespace App\Http\Controllers\Cirugia;

use App\models\CirugiaGrupo;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class CirugiaGrupoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $res = DB::table('cirugia_grupo')->get();
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
            'nombre' => 'required',          
        ];

        $this->validate($request, $rules);
        $resp = CirugiaGrupo::create($request->all());
        return $this->showOne($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\CirugiaGrupo  $cirugiaGrupo
     * @return \Illuminate\Http\Response
     */
    public function show(CirugiaGrupo $cirugiaGrupo)
    {
        return $this->showOne($cirugiaGrupo);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\CirugiaGrupo  $cirugiaGrupo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CirugiaGrupo $cirugiaGrupo)
    {
        $cirugiaGrupo->fill($request->only([
            'nombre',
    ]));

   if ($cirugiaGrupo->isClean()) {
        return $this->errorRepsonse('Se debe especificar al menos un valor', 422);
    }
   $cirugiaGrupo->save();
    return $this->showOne($cirugiaGrupo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\CirugiaGrupo  $cirugiaGrupo
     * @return \Illuminate\Http\Response
     */
    public function destroy(CirugiaGrupo $cirugiaGrupo)
    {
        //
    }
}
