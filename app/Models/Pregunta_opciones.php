<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta_opciones extends Model
{
    private $table = 'pregunta_opciones';
    
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }
}
