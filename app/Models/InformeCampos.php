<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformeCampos extends Model
{
    protected $table = 'informe_campos';
    protected $fillable = [
        'informe_id',
        'campo',
        'tipo',
        'titulo',
        'orden',
        'columna_id',
        'fila_id',
        'pregunta_id',
        'tipo_analisis',
    ];

    use SoftDeletes;

    public function informe()
    {
        return $this->belongsTo('App\Models\Informe');
    }
    public function pregunta()
    {
        return $this->belongsTo('App\Models\Pregunta', 'campo', 'id');
    }

    public function columna()
    {
        return $this->belongsTo('App\Models\Pregunta_columna', 'columna_id');
    }

    public function fila()
    {
        return $this->belongsTo('App\Models\Pregunta_filas', 'fila_id');
    }

}
