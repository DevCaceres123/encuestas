<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pregunta_opciones_columna extends Model
{
    use SoftDeletes;
      protected $fillable = [
        'columna_id',
        'opcion',       
    ];
    protected $table = 'pregunta_opciones_columnas';


}
