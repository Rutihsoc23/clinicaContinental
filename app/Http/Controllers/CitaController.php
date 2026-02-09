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
        $doctores = Doctor::with(['especialidad', 'disponibilidad.dia'])->get();
        $pacientes = Paciente::all();
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
}
