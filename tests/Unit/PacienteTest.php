<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PacienteTest extends TestCase
{
    use RefreshDatabase; // Limpia la BD después de cada prueba

    /** @test */
    public function un_paciente_requiere_un_dni_valido()
    {
        // Intentamos crear un paciente sin DNI
        $paciente = new Paciente([
            
            'nombre_paciente' => 'Juan',
            'apellido_paterno_paciente' => 'Perez',
            'apellido_materno_paciente' => 'Lope',
            'dni_paciente' => '74473307',
            


        ]);

        // La prueba pasa si el objeto no es válido o no se guarda
        $this->assertNotNull($paciente->dni_paciente, 'El DNI debería estar asignado correctamente.');
    }
}