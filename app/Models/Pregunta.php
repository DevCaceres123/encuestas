<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    //

    public function encuesta()
    {
        return $this->belongsTo('App\Models\Encuesta');
    }
    
    public function pregunta_opciones()
    {
        return $this->hasMany('App\Models\Pregunta_opciones');
    }   
}
