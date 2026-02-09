<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    // Indicar el nombre exacto de la tabla en la base de datos
    protected $table = 'especialidades';

    // Permitir la asignación masiva de este campo
    protected $fillable = ['nombre_especialidad'];
}
