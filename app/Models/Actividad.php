<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    public $table = "actividades";

    protected $fillable = ['titulo','descripcion','horario','etapa_educativa','cuota','image']; 

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
