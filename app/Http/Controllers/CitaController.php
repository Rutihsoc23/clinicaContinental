<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\EstadoCita;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    // Mostrar el formulario de nueva cita
    public function create()
    {
        // Eager Loading: Traemos al doctor con sus disponibilidades y los nombres de los días de un solo golpe
        $doctores = Doctor::select('id', 'nombre_doctor', 'apellido_paterno_doctor', 'especialidad_id')
        ->with([
            'especialidad:id,nombre_especialidad', // Solo traemos ID y Nombre de especialidad
            'disponibilidad' => function($query) {
                $query->select('id', 'doctor_id', 'dia_id', 'hora_inicio_disponibilidad', 'hora_fin_disponibilidad');
            },
            'disponibilidad.dia:id,nombre_dia' // Solo ID y nombre del día
        ])
        ->get();

        // 2. Optimización Pacientes
        // En lugar de 'all()', traemos solo lo necesario para identificar al paciente.
        $pacientes = Paciente::select('id', 'dni_paciente', 'nombre_paciente', 'apellido_paterno_paciente')
            ->orderBy('id', 'desc')
            ->limit(500)
            ->get();

        // Estados son pocos (3-5), no impacta el rendimiento.
        $estados = EstadoCita::all();

        return view('citas.create', compact('doctores', 'pacientes', 'estados'));
    }

    // Guardar la cita en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'doctor_id' => 'required|exists:doctores,id',
            'estado_id' => 'required|exists:estados_citas,id',
            'fecha_cita' => 'required|date|after_or_equal:today',
            'hora_cita' => 'required',
        ]);

        // Validación RF04: Cruce de horarios
        $existeCita = Cita::where('doctor_id', $request->doctor_id)
                          ->where('fecha_cita', $request->fecha_cita)
                          ->where('hora_cita', $request->hora_cita)
                          ->where('estado_id', '!=', 3) //  estado '3' - Cancelada, ignoramos esas.
                          ->exists();

        if ($existeCita) {
            // Regresamos al formulario con un error específico
            return back()
                ->withInput()
                ->withErrors(['hora_cita' => 'El especialista ya tiene una cita médica programada en este horario.']);
        }
        // Fin Validacion RF04

        Cita::create($request->all());

        return redirect()->route('citas.index')
                     ->with('success', '¡Cita agendada con éxito!');
    }
    public function index()
    {
        // Eager Loading: cargamos las relaciones para que el sistema sea más rápido (Eficiencia)
        $citas = Cita::with(['paciente', 'doctor', 'estado'])->orderBy('fecha_cita', 'desc')->get();

        return view('citas.index', compact('citas'));
    }

    public function destroy($id)
    {
        Cita::destroy($id);
        return redirect()->route('citas.index')->with('success', 'Cita cancelada y eliminada del sistema.');
    }
    /**
     * Muestra el formulario para editar el estado de la cita.
     */
    public function edit($id)
    {
        $cita = Cita::findOrFail($id);
        $estados = EstadoCita::all(); // Traemos todos los estados (Pendiente, Atendida, etc.)
        
        return view('citas.edit', compact('cita', 'estados'));
    }

    /**
     * Actualiza el estado en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'estado_id' => 'required|exists:estado_citas,id',
        ]);

        $cita = Cita::findOrFail($id);
        $cita->update([
            'estado_id' => $request->estado_id
        ]);

        return redirect()->route('citas.index')
                        ->with('success', 'El estado de la cita se actualizó correctamente.');
    }
}
