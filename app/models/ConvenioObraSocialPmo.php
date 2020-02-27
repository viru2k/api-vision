<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\ObraSocial;

class ConvenioObraSocialPmo extends Model
{
    protected $table = 'convenio_os_pmo';

    protected $fillable = [
        'obra_social_id',
        'pmo_id',  
        'valor',          
    ];

    public function obraSocial()
    {
        return $this->belongsTo(ObraSocial::class,'id');
    }
}