<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pregunta extends Model
{
    //
    use SoftDeletes;
    public function encuesta()
    {
        return $this->belongsTo('App\Models\Encuesta');
    }

    public function pregunta_opciones()
    {
        return $this->hasMany('App\Models\Pregunta_opciones');
    }

    // En el modelo Pregunta.php
    public function columnas()
    {
        return $this->hasMany(Pregunta_columna::class);
    }

    public function filas()
    {
        return $this->hasMany(Pregunta_filas::class);
    }
    public function informe_campos()
    {
        return $this->hasMany('App\Models\Informe_campo');
    }
}
