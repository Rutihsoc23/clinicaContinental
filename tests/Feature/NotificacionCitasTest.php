<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\EstadoCita;
use App\Models\Especialidad;
use App\Mail\RecordatorioCitaMailable;
use Carbon\Carbon;

class NotificacionCitasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Datos maestros mínimos
        Especialidad::create(['nombre_especialidad' => 'General']);
        EstadoCita::create(['id' => 1, 'nombre_estado' => 'Pendiente']);
        EstadoCita::create(['id' => 3, 'nombre_estado' => 'Cancelada']);
    }

    /** @test */
    public function el_comando_envia_correo_si_la_cita_es_en_una_hora()
    {
        // 1. Falsificar el envío de correos (Interceptarlos)
        Mail::fake();

        // 2. Configurar el "Ahora" del sistema
        // Congelamos el tiempo en una fecha específica para tener control total
        $now = Carbon::parse('2026-02-10 09:00:00');
        Carbon::setTestNow($now);

        // 3. Crear una cita para dentro de EXACTAMENTE 1 HORA (10:00:00)
        $cita = $this->crearCitaPara('2026-02-10', '10:00:00');

        // 4. Ejecutar el comando manualmente
        Artisan::call('citas:recordar');

        // 5. Aserción: Verificar que se intentó enviar un correo a ese paciente
        Mail::assertSent(RecordatorioCitaMailable::class, function ($mail) use ($cita) {
            return $mail->hasTo($cita->paciente->email_paciente) &&
                   $mail->cita->id === $cita->id;
        });
    }

    /** @test */
    public function el_comando_no_envia_correo_si_falta_mas_de_una_hora()
    {
        Mail::fake();

        $now = Carbon::parse('2026-02-10 09:00:00');
        Carbon::setTestNow($now);

        // Crear cita para dentro de 2 HORAS (11:00) -> No debería notificarse aún
        $this->crearCitaPara('2026-02-10', '11:00:00');

        Artisan::call('citas:recordar');

        // Aserción: No se debió enviar nada
        Mail::assertNothingSent();
    }

    // Helper
    private function crearCitaPara($fecha, $hora)
    {
        $doctor = Doctor::create([
            'nombre_doctor' => 'Gregory', 'apellido_paterno_doctor' => 'House', 'apellido_materno_doctor' => 'Md',
            'dni_doctor' => '12345678', 'cmp_doctor' => '99999', 'especialidad_id' => 1
        ]);

        $paciente = Paciente::create([
            'nombre_paciente' => 'Test', 'apellido_paterno_paciente' => 'User', 'apellido_materno_paciente' => 'X',
            'dni_paciente' => rand(10000000, 99999999), 'email_paciente' => 'test@mail.com'
        ]);

        return Cita::create([
            'doctor_id' => $doctor->id,
            'paciente_id' => $paciente->id,
            'estado_id' => 1, // Pendiente
            'fecha_cita' => $fecha,
            'hora_cita' => $hora
        ]);
    }
}
