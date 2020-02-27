<?php

namespace App\Http\Controllers\Estudio;

use App\models\Estudio;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class EstudioController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estudio = Estudio::all();
        return $this->showAll($estudio);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        'file' => 'required',            
       
    ];
    $this->validate($request, $rules);

    $data = $request->all();
    $data['nombre'] = $request->nombre;
    $data['image'] = $request->image->store('');
    $data['file'] = $request->file;
    $estudio = Estudio::create($data);
    return $this->showOne($estudio, 201);
}
    /**
     * Display the specified resource.
     *
     * @param  \App\models\Estudio  $estudio
     * @return \Illuminate\Http\Response
     */
    public function show(Estudio $estudio)
    {
        return $this->showOne($estudio);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Estudio  $estudio
     * @return \Illuminate\Http\Response
     */
    public function edit(Estudio $estudio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Estudio  $estudio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estudio $estudio)
    {
        $estudio->fill($request->only([
            'nombre',
            'file',           
    ]));

   if ($estudio->isClean()) {
    return $this->errorResponse('Se debe especificar al menos un valor', 422);
    }
   $estudio->save();
    return $this->showOne($estudio);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Estudio  $estudio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estudio $estudio)
    {
        //
    }
}
