<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisponibilidadEspecialista extends Model
{
    protected $table = 'disponibilidad_especialistas';

    protected $fillable = [
        'doctor_id', 'dia_id', 'hora_inicio_disponibilidad', 'hora_fin_disponibilidad'
    ];

    // El especialista PERTENECE a un día
    public function dia()
    {
        return $this->belongsTo(Dia::class, 'dia_id');
    }
}
