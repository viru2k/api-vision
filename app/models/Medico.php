<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Medico extends Model
{
    

  
    protected $table = 'medicos';

   protected $fillable = [
       
        'apellido',
        'nombre',
        'domicilio',
        'fecha_matricula',
        'cuit',
        'ing_brutos',
        'categoria_iva_id',
        'factura_documento_comprador_id',
        'punto_vta_id',
        'factura_comprobante_id',
    
    ];

    public function user(){
        
            return $this->belongsTo(User::class, 'usuario_id');
        }
    
}
