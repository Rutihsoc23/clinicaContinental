<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    protected $table = 'historia_clinicas';
    protected $fillable = ['cita_id', 'observaciones', 'diagnostico', 'tratamiento'];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }
}
