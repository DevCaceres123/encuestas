<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Afiliado extends Model
{

    public function comunidad(){
        return $this->belongsTo('App\Models\Comunidad');
    }
}
