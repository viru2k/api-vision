<?php

namespace App\Http\Controllers\ObraSocial;

use App\models\Pmo;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PmoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pmo = Pmo::all();
        return $this->showAll($pmo);
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
            'codigo' => 'required',          
            'descripcion' => 'required',          
            'complejidad' => 'required',  
           
        ];

        $this->validate($request, $rules);
        $resp = Pmo::create($request->all());
        return $this->showOne($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Pmo  $pmo
     * @return \Illuminate\Http\Response
     */
    public function show(Pmo $pmo)
    {
        return $this->showOne($pmo);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Pmo  $pmo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pmo = Pmo::findOrFail($id);
        $pmo->fill($request->only([
            'codigo',
            'descripcion',
            'complejidad',  
    ]));

   if ($pmo->isClean()) {
        return $this->errorRepsonse('Se debe especificar al menos un valor', 422);
    }
   $pmo->save();
    return $this->showOne($pmo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Pmo  $pmo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pmo $pmo)
    {
        //
    }
}
