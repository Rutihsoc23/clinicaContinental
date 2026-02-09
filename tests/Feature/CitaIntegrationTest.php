<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Cita;
use App\Models\Especialidad;
use App\Models\EstadoCita;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CitaIntegrationTest extends TestCase
{
    use RefreshDatabase; // Resetea la BD para que cada prueba sea limpia

    /** @test */
    public function una_cita_se_integra_correctamente_con_paciente_y_doctor()
    {
        // 1. Preparamos el entorno (Datos necesarios)
        $especialidad = Especialidad::create(['nombre_especialidad' => 'Cardiología']);
        $estado = EstadoCita::create(['nombre_estado' => 'Pendiente']);
        
        $doctor = Doctor::create([
            'nombre_doctor' => 'Ricardo',
            'apellido_paterno_doctor' => 'Maglioni',
            'apellido_materno_doctor' => 'Perez',
            'dni_doctor' => '12345678',
            'cmp_doctor' => 'CMP001',
            'especialidad_id' => $especialidad->id
        ]);

        $paciente = Paciente::create([
            'nombre_paciente' => 'Luis',
            'apellido_paterno_paciente' => 'Sánchez', // <--- AGREGAR ESTO
            'apellido_materno_paciente' => 'García',
            'dni_paciente' => '87654321',
            'email_paciente' => 'luis@correo.com'
        ]);

        // 2. Ejecutamos la integración: Agendamos la cita
        $cita = Cita::create([
            'fecha_cita' => '2026-02-01',
            'hora_cita' => '10:00:00',
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'estado_id' => $estado->id
        ]);

        // 3. Verificamos la integridad en la Base de Datos
        $this->assertDatabaseHas('citas', [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id
        ]);

        // Verificamos que el doctor de la cita sea efectivamente el Dr. Maglioni
        $this->assertEquals('Maglioni', $cita->doctor->apellido_paterno_doctor);
    }
}