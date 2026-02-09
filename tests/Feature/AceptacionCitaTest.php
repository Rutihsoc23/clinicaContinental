<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\EstadoCita;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AceptacionCitaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function el_administrativo_puede_agendar_una_cita_exitosamente()
    {
        // 1. CONFIGURACIÓN: Creamos los datos necesarios
        $esp = Especialidad::create(['nombre_especialidad' => 'Pediatría']);
        $doc = Doctor::create(['nombre_doctor' => 'Ana', 'apellido_paterno_doctor' => 'Luz', 'apellido_materno_doctor' => 'Ramos', 'dni_doctor' => '111', 'cmp_doctor' => 'CMP1', 'especialidad_id' => $esp->id]);
        $pac = Paciente::create(['nombre_paciente' => 'Santi', 'apellido_paterno_paciente' => 'Quispe', 'apellido_materno_paciente' => 'Mamani','dni_paciente' => '222', 'email_paciente' => 'santi@mail.com']);
        $est = EstadoCita::create(['nombre_estado' => 'Confirmada']);

        // 2. ACCIÓN: El usuario envía el formulario de agendamiento
        $response = $this->post(route('citas.store'), [
            'paciente_id' => $pac->id,
            'doctor_id'   => $doc->id,
            'fecha_cita'  => '2026-03-01',
            'hora_cita'   => '09:00',
            'estado_id'   => $est->id,
        ]);

        // 3. VALIDACIÓN: ¿Se guardó y nos redirigió al reporte?
        $response->assertRedirect(route('citas.index'));
        $this->assertDatabaseHas('citas', ['paciente_id' => $pac->id]);
    }
}