<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Pmo extends Model
{
    protected $table = 'pmo';

    protected $fillable = [
        'id',
        'codigo',
        'descripcion',
        'complejidad',
    ];
}
