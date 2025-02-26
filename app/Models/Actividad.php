<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'actividad_pertence');
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'solicitud_actividades');
    }
}
