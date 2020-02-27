<?php

namespace App\Http\Controllers\Concepto;

use App\models\Concepto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConceptoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Concepto  $concepto
     * @return \Illuminate\Http\Response
     */
    public function show(Concepto $concepto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Concepto  $concepto
     * @return \Illuminate\Http\Response
     */
    public function edit(Concepto $concepto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Concepto  $concepto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Concepto $concepto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Concepto  $concepto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Concepto $concepto)
    {
        //
    }

    public function categoriaIva(Request $request){
         
        
        $res = DB::table('categoria_iva')
      
        ->select(
            'id',
            'categoria_iva',

           )
              
            ->get();
           
        return $this->showAll($res);
}
}
