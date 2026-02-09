<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\EstadoCita;
use App\Models\Especialidad;
use Carbon\Carbon;

class ReporteCitasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Datos maestros necesarios
        Especialidad::create(['nombre_especialidad' => 'General']);
        EstadoCita::create(['id' => 1, 'nombre_estado' => 'Pendiente']);
        EstadoCita::create(['id' => 2, 'nombre_estado' => 'Atendida']);
    }

    /** @test */
    public function el_sistema_genera_reporte_diario_con_totales_correctos()
    {
        // 1. Preparar el escenario: Usuario Admin para acceder al reporte
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Crear datos de prueba
        $doctor = Doctor::create([
            'nombre_doctor' => 'Gregory', 'apellido_paterno_doctor' => 'House', 'apellido_materno_doctor' => 'Md',
            'dni_doctor' => '12345678', 'cmp_doctor' => '99999', 'especialidad_id' => 1
        ]);

        $paciente = Paciente::create([
            'nombre_paciente' => 'Test', 'apellido_paterno_paciente' => 'User', 'apellido_materno_paciente' => 'X',
            'dni_paciente' => '88888888', 'email_paciente' => 'test@mail.com'
        ]);

        // 3. Crear citas: 2 para HOY y 1 para MAÑANA
        Cita::create([ // Cita de Hoy #1
            'doctor_id' => $doctor->id, 'paciente_id' => $paciente->id, 'estado_id' => 1,
            'fecha_cita' => now()->format('Y-m-d'), 'hora_cita' => '09:00:00'
        ]);

        Cita::create([ // Cita de Hoy #2
            'doctor_id' => $doctor->id, 'paciente_id' => $paciente->id, 'estado_id' => 2,
            'fecha_cita' => now()->format('Y-m-d'), 'hora_cita' => '10:00:00'
        ]);

        Cita::create([ // Cita de MAÑANA (No debe salir en el reporte)
            'doctor_id' => $doctor->id, 'paciente_id' => $paciente->id, 'estado_id' => 1,
            'fecha_cita' => now()->addDay()->format('Y-m-d'), 'hora_cita' => '09:00:00'
        ]);

        // 4. Acción: Consultar la ruta del reporte diario
        $response = $this->actingAs($admin)
                         ->get(route('reportes.diario'));

        // 5. Aserción
        $response->assertStatus(200);

        // Verificamos que a la vista se le pasen exactamente 2 citas (las de hoy)
        $response->assertViewHas('citasDelDia', function ($citas) {
            return $citas->count() === 2;
        });

        // Verificamos que se calculen los totales (Opcional, si tu controller los pasa)
        $response->assertViewHas('totalCitas', 2);
    }
}
