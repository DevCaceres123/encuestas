<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Miembros_familia extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = "miembros_familia";
    public function afiliado()
    {
        return $this->belongsTo('App\Models\Afiliado');
    }
}
