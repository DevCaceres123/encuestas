<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    use HasFactory;
    public  function users(){
       

        return $this->belongsToMany('App\Models\User', 'user_reunion', 'id_reunion', 'id_usuario')
        ->withPivot('id_lector'); // Incluimos el campo extra (id_lectores)

    }

}
