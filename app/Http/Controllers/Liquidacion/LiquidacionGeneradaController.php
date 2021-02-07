<?php

namespace App\Http\Controllers\Liquidacion;

use App\models\LiquidacionGenerada;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class LiquidacionGeneradaController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

   
    {
        $liquidacionGenerada = LiquidacionGenerada::all();
        return $this->showAll($liquidacionGenerada);
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
            'numero' => 'required',
            'fecha_liquidacion' => 'required',            
            'estado' => 'required',
        ];

        $this->validate($request, $rules);
        $liquidacionGenerada = LiquidacionGenerada::create($request->all());
        return $this->showOne($liquidacionGenerada, 201);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\LiquidacionGenerada  $liquidacionGenerada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  

        $liquidacionGenerada = LiquidacionGenerada::findOrFail($id);
        $liquidacionGenerada->fill($request->only([
            'numero',
            'fecha_liquidacion',               
            'estado', 
    ]));

   if ($liquidacionGenerada->isClean()) {
        return $this->errorResponse('Se debe especificar al menos un valor', 422);
    }
   $liquidacionGenerada->save();
    return $this->showOne($liquidacionGenerada);

}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\LiquidacionGenerada  $liquidacionGenerada
     * @return \Illuminate\Http\Response
     */
    public function destroy(LiquidacionGenerada $liquidacionGenerada)
    {
        //
    }
}
