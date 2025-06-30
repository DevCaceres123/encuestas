<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pregunta_filas extends Model
{
    use SoftDeletes;
    protected $table = 'pregunta_filas';
    protected $fillable = [
           'pregunta_id',
           'columna_id',
           'pregunta',
           'orden',
       ];
}
