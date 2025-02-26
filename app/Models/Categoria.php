<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'actividad_pertence');
    }
}
