<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expedido extends Model
{
    protected $table='expedidos';

    public function afiliados(){
        $this->hasMany('App\Models\Afiliado');
    }
}
