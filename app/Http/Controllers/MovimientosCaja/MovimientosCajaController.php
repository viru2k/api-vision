<?php

namespace App\Http\Controllers\MovimientosCaja;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

use App\models\ConceptoMoneda; 
use App\models\ConceptoCuenta; 
use App\models\ConceptoTipoComprobante; 
use App\models\Cuenta; 


class MovimientosCajaController extends ApiController
{
    public function getConceptoMonedas()
    {
        $conceptoMoneda = ConceptoMoneda::all();
        return $this->showAll($conceptoMoneda);
    }

    public function getConceptoCuentas()
    {
        $conceptoCuenta = ConceptoCuenta::all();
        return $this->showAll($conceptoCuenta);
    }

    public function getCuentas()
    {
        $cuenta = Cuenta::all();
        return $this->showAll($cuenta);
    }

    public function getConceptoTipoComprobantes()
    {
        $cuenta = ConceptoTipoComprobante::all();
        return $this->showAll($cuenta);
    }






    public function getConceptoMoneda(ConceptoMoneda $Sector)
    {
        return $this->showOne($Sector);
    }

    public function getConceptoCuenta(ConceptoCuenta $Sector)
    {
        return $this->showOne($Sector);
    }

    public function getConceptoTipoComprobante(ConceptoTipoComprobante $Sector)
    {
        return $this->showOne($Sector);
    }


    public function getCuenta(Cuenta $Sector)
    {
        return $this->showOne($Sector);
    }




    public function setConceptoMoneda(Request $request)
    {
        $id= DB::table('mov_tipo_moneda')->insertGetId([
            'tipo_moneda' => $request->tipo_moneda,           
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    

        ]);
        $resp = ConceptoMoneda::find($id);
        return $this->showOne($resp);
    }

    public function setConceptoCuenta(Request $request)
    {
        $id= DB::table('mov_tipo_moneda')->insertGetId([
            'concepto_cuenta' => $request->concepto_cuenta,                       
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    

        ]);
        $resp = ConceptoCuenta::find($id);
        return $this->showOne($resp);        
    }

    
    public function setConceptoTipoComprobante(Request $request)
    {
        $id= DB::table('mov_tipo_comprobante')->insertGetId([
            'tipo_comprobante' => $request->tipo_comprobante,                       
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    

        ]);
        $resp = ConceptoTipoComprobante::find($id);
        return $this->showOne($resp);        
    }


    public function setCuenta(Request $request)
    {
        $id= DB::table('mov_cuenta')->insertGetId([
            'cuenta_nombre' => $request->cuenta_nombre,                       
            'movimiento_tipo' => $request->movimiento_tipo,                       
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")    

        ]);
        $resp = Cuenta::find($id);
        return $this->showOne($resp);  
    }



    public function putConceptoMoneda(Request $request, $id)
    {
        $pmo = ConceptoMoneda::findOrFail($id);
        $pmo->fill($request->only([
            'tipo_moneda'

    ]));

   if ($pmo->isClean()) {
        return $this->errorRepsonse('Se debe especificar al menos un valor', 422);
    }
   $pmo->save();
    return $this->showOne($pmo);
    }

    public function putConceptoCuenta(Request $request, $id)
    {
        $pmo = ConceptoCuenta::findOrFail($id);
        $pmo->fill($request->only([
            'concepto_cuenta'

    ]));

   if ($pmo->isClean()) {
        return $this->errorRepsonse('Se debe especificar al menos un valor', 422);
    }
   $pmo->save();
    return $this->showOne($pmo);
    }

    public function putConceptoTipoComprobante(Request $request, $id)
    {
        $pmo = ConceptoTipoComprobante::findOrFail($id);
        $pmo->fill($request->only([
            'tipo_comprobante'

    ]));

   if ($pmo->isClean()) {
        return $this->errorRepsonse('Se debe especificar al menos un valor', 422);
    }
   $pmo->save();
    return $this->showOne($pmo);
    }


    public function putCuenta(Request $request, $id)
    {
        $pmo = Cuenta::findOrFail($id);
        $pmo->fill($request->only([
            'cuenta_nombre',
            'movimiento_tipo'

    ]));

   if ($pmo->isClean()) {
        return $this->errorRepsonse('Se debe especificar al menos un valor', 422);
    }
   $pmo->save();
    return $this->showOne($pmo);
    }
    


}
