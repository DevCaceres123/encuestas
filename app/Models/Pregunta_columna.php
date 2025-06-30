<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pregunta_columna extends Model
{
    use SoftDeletes;
    protected $table = 'pregunta_columnas';
    protected $fillable = [
            'pregunta_id',
            'pregunta',
            'tipo',
            'orden',
        ];
    
    public function preguntas()
    {
        return $this->hasMany(Pregunta_filas::class, 'columna_id');
    }
}
