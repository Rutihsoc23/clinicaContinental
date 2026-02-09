<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $fillable = [
        'nombre_paciente',
        'apellido_paterno_paciente',
        'apellido_materno_paciente',
        'dni_paciente',
        'telefono_paciente',
        'email_paciente'
    ];
}
