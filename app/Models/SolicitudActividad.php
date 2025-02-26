<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudActividad extends Model
{
    public $table = "solicitud_actividades";
    use HasFactory;
    protected $fillable = ['estado','fecha','actividad_id','alumno_id']; 
}
