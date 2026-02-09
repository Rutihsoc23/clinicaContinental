<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctores';

    protected $fillable = [
        'nombre_doctor', 'apellido_paterno_doctor', 'apellido_materno_doctor', 
        'dni_doctor', 'cmp_doctor', 'especialidad_id'
    ];

    // Relación: Un doctor pertenece a una especialidad
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    public function disponibilidad()
    {
        return $this->hasMany(DisponibilidadEspecialista::class, 'doctor_id');
    }
}