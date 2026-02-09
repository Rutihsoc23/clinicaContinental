<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\EstadoCita;
use App\Models\Cita;

class AgendamientoCitasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Datos maestros necesarios
        Especialidad::create(['nombre_especialidad' => 'General']);
        EstadoCita::create(['nombre_estado' => 'Pendiente']);
    }

    /** @test */
    public function se_puede_agendar_una_cita_si_el_horario_esta_libre()
    {
        // 1. Crear Doctor y Paciente
        $doctor = Doctor::create([
            'nombre_doctor' => 'Gregory',
            'apellido_paterno_doctor' => 'House',
            'apellido_materno_doctor' => 'Md',
            'dni_doctor' => '11111111',
            'cmp_doctor' => '12345',
            'especialidad_id' => 1
        ]);

        $paciente = Paciente::create([
            'nombre_paciente' => 'Wilson',
            'apellido_paterno_paciente' => 'Castaway',
            'apellido_materno_paciente' => 'Movie',
            'dni_paciente' => '22222222',
            'email_paciente' => 'wilson@test.com'
        ]);

        // 2. Datos de la cita
        $fechaFutura = now()->addWeek()->format('Y-m-d'); // Una fecha válida (futuro)

        $datosCita = [
            'doctor_id' => $doctor->id,
            'paciente_id' => $paciente->id,
            'estado_id' => 1, // Pendiente
            'fecha_cita' => $fechaFutura,
            'hora_cita' => '10:00:00', // Hora específica
            'observaciones_cita' => 'Dolor de cabeza'
        ];

        // 3. Acción
        $response = $this->post(route('citas.store'), $datosCita);

        // 4. Aserción
        $response->assertRedirect(route('citas.index'));
        $this->assertDatabaseHas('citas', [
            'doctor_id' => $doctor->id,
            'fecha_cita' => $fechaFutura,
            'hora_cita' => '10:00:00'
        ]);
    }

    /** @test */
    public function no_se_puede_agendar_si_ya_existe_cruce_de_horario()
    {
        // 1. Setup: Crear Doctor, Paciente y UNA CITA PREVIA
        $doctor = Doctor::create([
            'nombre_doctor' => 'Strange',
            'apellido_paterno_doctor' => 'Stephen',
            'apellido_materno_doctor' => 'Marvel',
            'dni_doctor' => '33333333',
            'cmp_doctor' => '54321',
            'especialidad_id' => 1
        ]);

        $paciente = Paciente::create([
            'nombre_paciente' => 'Tony',
            'apellido_paterno_paciente' => 'Stark',
            'apellido_materno_paciente' => 'Iron',
            'dni_paciente' => '44444444',
            'email_paciente' => 'tony@test.com'
        ]);

        $fecha = now()->addWeek()->format('Y-m-d');
        $hora = '10:00:00';

        // CREAMOS LA CITA QUE OCUPA EL ESPACIO
        Cita::create([
            'doctor_id' => $doctor->id,
            'paciente_id' => $paciente->id,
            'estado_id' => 1,
            'fecha_cita' => $fecha,
            'hora_cita' => $hora
        ]);

        // 2. Intentamos agendar OTRA cita con el MISMO doctor a la MISMA hora
        $response = $this->post(route('citas.store'), [
            'doctor_id' => $doctor->id,
            'paciente_id' => $paciente->id, // Puede ser el mismo paciente u otro
            'estado_id' => 1,
            'fecha_cita' => $fecha,
            'hora_cita' => $hora, // ¡Conflicto!
        ]);

        // 3. Aserción: Esperamos que falle y regrese con errores
        $response->assertSessionHasErrors(['hora_cita']); // O el campo donde pongamos el error

        // Aseguramos que solo haya 1 cita en la BD, no 2
        $this->assertCount(1, Cita::where('doctor_id', $doctor->id)->get());
    }
}
