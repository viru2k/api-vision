<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class MedicoObraSocial extends Model
{
    
    protected $table = 'medicos_os';

   protected $fillable = [
       
        'medico_id',
        'obra_social_id'        
    ];
}
