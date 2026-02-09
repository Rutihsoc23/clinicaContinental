<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecordatorioCitaMailable;
use Carbon\Carbon;

class EnviarRecordatoriosCitas extends Command
{
    // El nombre que usaremos para llamar al comando
    protected $signature = 'citas:recordar';
    protected $description = 'Envía correos a pacientes con citas en la próxima hora';

    public function handle()
    {
        // 1. Calculamos la hora objetivo (Exactamente dentro de 1 hora)
        // Nota: En producción, podrías usar un rango (between) para ser más tolerante con los minutos.
        $targetTime = Carbon::now()->addHour();

        $fechaTarget = $targetTime->format('Y-m-d');
        $horaTarget  = $targetTime->format('H:i:00'); // Asumimos que las citas son en punto o medias horas (00 sec)

        $this->info("Buscando citas para: $fechaTarget a las $horaTarget");

        // 2. Buscar citas que coincidan y NO estén canceladas
        // Asumiendo estado 3 = Cancelada. Ajusta según tus IDs.
        $citas = Cita::with('paciente', 'doctor')
                    ->whereDate('fecha_cita', $fechaTarget)
                    ->whereTime('hora_cita', $horaTarget)
                    ->where('estado_id', '!=', 3)
                    ->get();

        // 3. Iterar y enviar correos
        foreach ($citas as $cita) {
            Mail::to($cita->paciente->email_paciente)
                ->send(new RecordatorioCitaMailable($cita));

            $this->info("Correo enviado a: {$cita->paciente->email_paciente}");
        }

        $this->info("Total enviado: " . $citas->count());
    }
}
