<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    public $table = "alumnos";
    use HasFactory;
    protected $fillable = ['nombre','apellidos','nombre_responsable','apellido_responsable','email_responsable','telefono_responsable']; 
    
    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'solicitud_actividades');
    }
}
