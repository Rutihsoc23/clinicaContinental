<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Especialidad;
use App\Models\EstadoCita;
use App\Models\Dia;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\DisponibilidadEspecialista;
use App\Models\Cita;

class ClinicaSeeder extends Seeder
{
    public function run()
    {
        // 1. Llenar Especialidades
        $esp1 = Especialidad::create(['nombre_especialidad' => 'Cardiología']);
        $esp2 = Especialidad::create(['nombre_especialidad' => 'Pediatría']);
        $esp3 = Especialidad::create(['nombre_especialidad' => 'Ginecología']);

        // 2. Llenar Estados de Cita
        $est1 = EstadoCita::create(['nombre_estado' => 'Pendiente']);
        $est2 = EstadoCita::create(['nombre_estado' => 'Confirmada']);
        $est3 = EstadoCita::create(['nombre_estado' => 'Cancelada']);

        // 3. Llenar Días
        $diasArr = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        foreach ($diasArr as $d) {
            Dia::create(['nombre_dia' => $d]);
        }

        // 4. Llenar Doctores (Con distintivos)
        $doc1 = Doctor::create([
            'nombre_doctor' => 'Maglioni',
            'apellido_paterno_doctor' => 'Arana',
            'apellido_materno_doctor' => 'Caparachin',
            'dni_doctor' => '12345678',
            'cmp_doctor' => 'CMP1010',
            'especialidad_id' => $esp1->id
        ]);

        $doc2 = Doctor::create([
            'nombre_doctor' => 'Juan',
            'apellido_paterno_doctor' => 'Perez',
            'apellido_materno_doctor' => 'Soto',
            'dni_doctor' => '87654321',
            'cmp_doctor' => 'CMP2020',
            'especialidad_id' => $esp2->id
        ]);

        // 5. Llenar Pacientes
        $pac1 = Paciente::create([
            'nombre_paciente' => 'Carlos',
            'apellido_paterno_paciente' => 'Gomez',
            'apellido_materno_paciente' => 'Ruiz',
            'dni_paciente' => '44556677',
            'telefono_paciente' => '987654321',
            'email_paciente' => 'carlos@gmail.com'
        ]);

        // 6. Llenar Disponibilidad (Cruce)
        DisponibilidadEspecialista::create([
            'doctor_id' => $doc1->id,
            'dia_id' => 1, // Lunes
            'hora_inicio_disponibilidad' => '08:00:00',
            'hora_fin_disponibilidad' => '12:00:00'
        ]);

        // 7. Crear una Cita de prueba (Transaccional)
        Cita::create([
            'paciente_id' => $pac1->id,
            'doctor_id' => $doc1->id,
            'estado_id' => $est1->id, // Pendiente
            'fecha_cita' => '2026-02-15',
            'hora_cita' => '09:00:00',
            'observaciones_cita' => 'Consulta general de control.'
        ]);
    }
}