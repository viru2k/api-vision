<?php

namespace App\Http\Controllers\Liquidacion;

use App\models\EntidadFactura;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class EntidadFacturaController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidadFactura = EntidadFactura::all();
        return $this->showAll($entidadFactura);
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
        $entidadFactura = EntidadFactura::create($request->all());
        return $this->showOne($entidadFactura, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\EntidadFactura  $entidadFactura
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
              
      $res= DB::table('entidad')    
      ->where('entidad.id','=', $id)
      ->get();
  return $this->showAll($res);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\EntidadFactura  $entidadFactura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { $entidadFactura = EntidadFactura::findOrFail($id);
        $entidadFactura->fill($request->only([
            'nombre',
          
    ]));

    if ($entidadFactura->isClean()) {
        return $this->errorResponse('Se debe especificar al menos un valor', 422);
    }
   $entidadFactura->save();
    return $this->showOne($entidadFactura);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\EntidadFactura  $entidadFactura
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntidadFactura $entidadFactura)
    {
        //
    }
}
