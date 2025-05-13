<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function afiliado()
    {
        return $this->belongsTo(Afiliado::class);
    }
    public function pregunta_opciones()
    {
        return $this->belongsTo(Pregunta_opciones::class);
    }
}
