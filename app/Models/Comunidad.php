<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comunidad extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = 'comunidades';


    public function distrito()
    {

        return $this->belongsTo('App\Models\Distrito');
    }

    public function afiliados()
    {

        return $this->hasMany('App\Models\Afiliado');
    }
}
