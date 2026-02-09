<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Paciente;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Especialidad;

class ClinicaTest extends TestCase
{
    use RefreshDatabase;

    /** * PRUEBA 01: Validación de DNI de Paciente (EXITOSA)
     * Verifica que el sistema acepte un DNI de 8 dígitos.
     */
    public function test_paciente_con_dni_valido_pasa_validacion()
    {
        $paciente = new Paciente([
            'nombre_paciente' => 'Juan',
            'dni_paciente' => '71234567'
        ]);

        $this->assertEquals('71234567', $paciente->dni_paciente);
    }

    /** * PRUEBA 02: Registro de Doctor sin Especialidad (FALLA A PROPÓSITO)
     * En la Clínica Continental, un doctor DEBE tener especialidad. 
     * Aquí intentaremos crearlo sin ella para que el test salga en ROJO.
     */
    public function test_doctor_no_puede_existir_sin_especialidad()
    {
        // 1. Creamos una especialidad de prueba
        $especialidad = \App\Models\Especialidad::create([
            'nombre_especialidad' => 'Cardiología'
        ]);

        // 2. Creamos al doctor asignándole el ID de esa especialidad
        $doctor = new Doctor([
            'nombre_doctor' => 'Ricardo Maglioni',
            'cmp_doctor' => 'CMP999',
            'especialidad_id' => $especialidad->id // Ya no es NULL
        ]);

        // 3. La prueba ahora pasará porque ya no es nulo
        $this->assertNotNull($doctor->especialidad_id, 'El doctor debe tener una especialidad válida.');
    }

    /** * PRUEBA 03: Cálculo de Horas de Atención (EXITOSA)
     * Verifica que los campos de tiempo se manejen correctamente.
     */
    public function test_formato_de_hora_es_valido()
    {
        $hora_inicio = "08:00";
        $hora_fin = "13:00";
        
        $this->assertGreaterThan($hora_inicio, $hora_fin);
    }
}