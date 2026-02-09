<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'estado_id',
        'fecha_cita',
        'hora_cita',
        'observaciones_cita'
    ];

    // Relaciones para facilitar consultas
    public function paciente() { return $this->belongsTo(Paciente::class, 'paciente_id'); }
    public function doctor() { return $this->belongsTo(Doctor::class, 'doctor_id'); }
    public function estado() { return $this->belongsTo(EstadoCita::class, 'estado_id'); }
}
