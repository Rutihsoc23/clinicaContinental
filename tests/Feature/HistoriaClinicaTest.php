<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User; // Asumiremos autenticación básica más adelante
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\Cita;
use App\Models\EstadoCita;
use App\Models\Especialidad;

class HistoriaClinicaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Datos maestros
        Especialidad::create(['nombre_especialidad' => 'General']);
        // Definimos los estados clave
        EstadoCita::create(['id' => 1, 'nombre_estado' => 'Pendiente']);
        EstadoCita::create(['id' => 2, 'nombre_estado' => 'Atendida']);
        EstadoCita::create(['id' => 3, 'nombre_estado' => 'Cancelada']);
    }

    /** @test */
    public function no_se_puede_crear_historia_si_la_cita_es_pendiente()
    {
        // 1. Crear Cita Pendiente
        $cita = $this->crearCitaDePrueba(1); // Estado 1 = Pendiente

        // 2. Intentar acceder a la ruta de crear historia para esa cita
        // Ruta hipotética: /citas/{id}/historia/create
        $response = $this->get(route('historias.create', $cita->id));

        // 3. Aserción: Debe prohibir el acceso (403 Forbidden) o redirigir con error
        $response->assertStatus(403);
    }

    /** @test */
    public function se_habilita_registro_historia_si_cita_esta_atendida()
    {
        // 1. Crear Cita Atendida
        $cita = $this->crearCitaDePrueba(2); // Estado 2 = Atendida

        // 2. Acceder a la ruta
        $response = $this->get(route('historias.create', $cita->id));

        // 3. Aserción: Debe permitir entrar (200 OK)
        $response->assertStatus(200);
        $response->assertViewIs('historias.create');
    }

    /** @test */
    public function se_guarda_correctamente_la_historia_clinica()
    {
        $cita = $this->crearCitaDePrueba(2); // Atendida

        $datosHistoria = [
            'cita_id' => $cita->id,
            'observaciones' => 'Paciente presenta fiebre.',
            'diagnostico' => 'Infección viral.',
            'tratamiento' => 'Paracetamol 500mg c/8h'
        ];

        $response = $this->post(route('historias.store'), $datosHistoria);

        $response->assertRedirect(route('citas.index'));
        $this->assertDatabaseHas('historia_clinicas', [
            'cita_id' => $cita->id,
            'diagnostico' => 'Infección viral.'
        ]);
    }

    // Función auxiliar para no repetir código
    private function crearCitaDePrueba($estadoId)
    {
        $doctor = Doctor::create([
            'nombre_doctor' => 'Gregory', 'apellido_paterno_doctor' => 'House', 'apellido_materno_doctor' => 'Md',
            'dni_doctor' => '12345678', 'cmp_doctor' => '99999', 'especialidad_id' => 1
        ]);

        $paciente = Paciente::create([
            'nombre_paciente' => 'Test', 'apellido_paterno_paciente' => 'User', 'apellido_materno_paciente' => 'X',
            'dni_paciente' => rand(10000000, 99999999), 'email_paciente' => 'test'.rand(1,100).'@mail.com'
        ]);

        return Cita::create([
            'doctor_id' => $doctor->id,
            'paciente_id' => $paciente->id,
            'estado_id' => $estadoId,
            'fecha_cita' => now()->format('Y-m-d'),
            'hora_cita' => '10:00:00'
        ]);
    }
}
