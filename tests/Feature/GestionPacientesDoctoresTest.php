<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\Dia;

class GestionPacientesDoctoresTest extends TestCase
{
    // Este trait migra la BD al iniciar el test y la borra al terminar
    // Garantiza un entorno limpio para cada prueba.
    use RefreshDatabase;

    /** * Setup inicial para crear datos dependientes (Especialidades y Días)
     * necesarios para los doctores.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Creamos datos base que el DoctorController necesita
        Especialidad::create(['nombre_especialidad' => 'Cardiología']); // Asegúrate que tu modelo Especialidad tenga 'nombre' en fillable
        Dia::create(['nombre_dia' => 'Lunes']);
        Dia::create(['nombre_dia' => 'Martes']);
    }

    // --- PRUEBAS PARA PACIENTES (RF01) ---

    /** @test */
    public function un_paciente_puede_ser_registrado_correctamente()
    {
        // 1. Datos de prueba
        $datosPaciente = [
            'nombre_paciente' => 'Juan',
            'apellido_paterno_paciente' => 'Perez',
            'apellido_materno_paciente' => 'Gomez',
            'dni_paciente' => '12345678',
            'telefono_paciente' => '999888777',
            'email_paciente' => 'juan@test.com'
        ];

        // 2. Acción: Enviar POST a la ruta store
        // Asumiendo que tu ruta es 'pacientes.store'
        $response = $this->post(route('pacientes.store'), $datosPaciente);

        // 3. Aserciones (Verificaciones)
        $response->assertRedirect(route('pacientes.index')); // Verifica redirección
        $this->assertDatabaseHas('pacientes', [ // Verifica que esté en la BD
            'dni_paciente' => '12345678',
            'email_paciente' => 'juan@test.com'
        ]);
    }

    /** @test */
    public function no_se_puede_registrar_paciente_con_dni_duplicado()
    {
        // Crear un paciente primero
        Paciente::create([
            'nombre_paciente' => 'Existente',
            'apellido_paterno_paciente' => 'Test',
            'apellido_materno_paciente' => 'Test',
            'dni_paciente' => '12345678', // DNI Ocupado
            'email_paciente' => 'otro@test.com'
        ]);

        // Intentar crear otro con el mismo DNI
        $datosNuevoPaciente = [
            'nombre_paciente' => 'Nuevo',
            'apellido_paterno_paciente' => 'User',
            'apellido_materno_paciente' => 'User',
            'dni_paciente' => '12345678', // Repetido
            'email_paciente' => 'nuevo@test.com'
        ];

        $response = $this->post(route('pacientes.store'), $datosNuevoPaciente);

        // Debe haber error de validación en 'dni_paciente'
        $response->assertSessionHasErrors('dni_paciente');
    }

    /** @test */
    public function un_paciente_puede_ser_actualizado()
    {
        $paciente = Paciente::create([
            'nombre_paciente' => 'Original',
            'apellido_paterno_paciente' => 'Test',
            'apellido_materno_paciente' => 'Test',
            'dni_paciente' => '87654321',
            'email_paciente' => 'original@test.com'
        ]);

        $datosActualizados = [
            'nombre_paciente' => 'Editado',
            'apellido_paterno_paciente' => 'Test',
            'apellido_materno_paciente' => 'Test',
            'dni_paciente' => '87654321', // Mismo DNI
            'email_paciente' => 'editado@test.com' // Nuevo correo
        ];

        $response = $this->put(route('pacientes.update', $paciente->id), $datosActualizados);

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseHas('pacientes', ['email_paciente' => 'editado@test.com']);
    }

    /** @test */
    public function un_paciente_puede_ser_eliminado()
    {
        $paciente = Paciente::create([
            'nombre_paciente' => 'Borrar',
            'apellido_paterno_paciente' => 'Test',
            'apellido_materno_paciente' => 'Test',
            'dni_paciente' => '11112222',
            'email_paciente' => 'borrar@test.com'
        ]);

        $response = $this->delete(route('pacientes.destroy', $paciente->id));

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseMissing('pacientes', ['id' => $paciente->id]);
    }


    // --- PRUEBAS PARA DOCTORES (RF02) ---

    /** @test */
    public function un_doctor_puede_ser_registrado_con_disponibilidad()
    {
        // Obtenemos IDs auxiliares creados en setUp
        $especialidadId = Especialidad::first()->id;
        $diaId = Dia::first()->id;

        $datosDoctor = [
            'nombre_doctor' => 'Gregory',
            'apellido_paterno_doctor' => 'House',
            'apellido_materno_doctor' => 'Md',
            'dni_doctor' => '55555555',
            'cmp_doctor' => '12345',
            'especialidad_id' => $especialidadId,
            // Datos para la tabla pivote/relación
            'dias' => [$diaId],
            'hora_inicio' => '08:00',
            'hora_fin' => '12:00'
        ];

        $response = $this->post(route('doctores.store'), $datosDoctor);

        $response->assertRedirect(route('doctores.index'));

        // 1. Validar tabla doctores
        $this->assertDatabaseHas('doctores', [
            'dni_doctor' => '55555555',
            'cmp_doctor' => '12345'
        ]);

        // 2. Validar que se creó la disponibilidad (RF03 implícito en el guardado)
        // Ojo: Asumiendo que tu tabla se llama 'disponibilidad_especialistas' o similar
        // Verifica el nombre de la tabla en tu migración de disponibilidad
        $doctor = Doctor::where('dni_doctor', '55555555')->first();

        $this->assertDatabaseHas('disponibilidad_especialistas', [ // Ajusta el nombre de tabla si es diferente
            'doctor_id' => $doctor->id,
            'dia_id' => $diaId,
            'hora_inicio_disponibilidad' => '08:00'
        ]);
    }
}
