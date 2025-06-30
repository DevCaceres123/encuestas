<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pregunta_opciones extends Model
{
    use SoftDeletes;
    
    protected $table = 'pregunta_opciones';
    protected $fillable = [
        'pregunta_id',
        'opcion',
        // agrega aquí otros campos si los tienes, como 'estado', etc.
    ];
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }
}
