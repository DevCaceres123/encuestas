<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Encuesta extends Model
{
    use SoftDeletes;

    protected $appends = ['created_at_formateado'];

    // Asegurarse de que 'created_at' sea un objeto Carbon al obtenerlo
    protected $dates = ['created_at', 'updated_at']; // Esto asegura que Eloquent lo interprete correctamente como un objeto Carbon


    public function preguntas()
    {
        return $this->hasMany('App\Models\Pregunta');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getCreatedAtFormateadoAttribute()
    {
        if (empty($this->created_at)) {
            return 'N/A';
        }
    
        // Asegurar que $this->created_at sea un objeto Carbon
        $fecha = $this->created_at instanceof Carbon
            ? $this->created_at
            : Carbon::parse($this->created_at);
    
        return $fecha->locale('es')->translatedFormat('d \d\e F \d\e Y H:i');
    }

    public function informe()
    {
        return $this->hasOne('App\Models\Informe');
    }
}
