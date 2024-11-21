<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Afiliado extends Model
{
   
    use HasFactory;
    use SoftDeletes;
    public function comunidad(){
        return $this->belongsTo('App\Models\Comunidad');
    }

    public function numero_familia(){
        return $this->hasOne('App\Models\Miembros_familia');
    }

    public function expedido(){
        return $this->belongsTo('App\Models\Expedido');
    }


}
