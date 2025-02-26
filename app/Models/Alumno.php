<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;
    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'solicitud_actividades');
    }
}
