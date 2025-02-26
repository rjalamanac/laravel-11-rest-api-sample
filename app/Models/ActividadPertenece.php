<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadPertenece extends Model
{
    protected $fillable = ['actividad_id','categoria_id']; 

    public $table = "actividad_pertence";
    use HasFactory;
}
