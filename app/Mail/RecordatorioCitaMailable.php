<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioCitaMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;

    // Recibimos la cita en el constructor
    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function build()
    {
        // Usamos una vista simple o texto plano para la prueba
        return $this->subject('Recordatorio de Cita Médica')
                    ->html("Hola {$this->cita->paciente->nombre_paciente}, recuerda tu cita con el Dr. {$this->cita->doctor->nombre_doctor} a las {$this->cita->hora_cita}.");
    }
}
