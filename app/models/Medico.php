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
    
    ];

    public function user(){
        
            return $this->belongsTo(User::class, 'usuario_id');
        }
    
}
