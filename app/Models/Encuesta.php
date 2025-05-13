<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    //

    public function preguntas()
    {
        return $this->hasMany('App\Models\Pregunta');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
