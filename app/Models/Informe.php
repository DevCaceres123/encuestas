<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
      protected $fillable = [
        'encuesta_id',
        'titulo',       
    ];
    public function encuesta()
    {
        return $this->belongsTo('App\Models\Encuesta');
    }

    public function informe_campos() //
    {
        return $this->hasMany('App\Models\Informe_campo');
    }

}
