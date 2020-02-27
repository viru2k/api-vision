<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Estudio extends Model
{
    protected $table = 'estudios_imagen';

    protected $fillable = [  
         'nombre',
         'file',
         'image',
     ];
}
