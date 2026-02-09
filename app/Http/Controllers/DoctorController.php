<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\Dia;
use App\Models\DisponibilidadEspecialista;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        // Eager Loading para evitar que las relaciones lleguen nulas por error de carga
        $doctores = Doctor::with('disponibilidad.dia')->get();
        return view('doctores.index', compact('doctores'));
    }

    public function create()
    {
        $especialidades = Especialidad::all();
        $dias = Dia::all();
        return view('doctores.create', compact('especialidades', 'dias'));
    }

    public function store(Request $request)
    {
        // 1. Validar (Si algo falla aquí, ahora lo verás en la pantalla)
        $request->validate([
            'dni_doctor' => 'required|unique:doctores,dni_doctor',
            'cmp_doctor' => 'required|unique:doctores,cmp_doctor',
            'dias' => 'required|array', // Validamos que al menos marque un día
        ]);

        // 2. Crear el Doctor
        $doctor = Doctor::create([
            'nombre_doctor' => $request->nombre_doctor,
            'apellido_paterno_doctor' => $request->apellido_paterno_doctor,
            'apellido_materno_doctor' => $request->apellido_materno_doctor,
            'dni_doctor' => $request->dni_doctor,
            'cmp_doctor' => $request->cmp_doctor,
            'especialidad_id' => $request->especialidad_id,
        ]);

        // 3. Guardar todos los días marcados (Interdiario, fin de semana, etc.)
        foreach ($request->dias as $dia_id) {
            DisponibilidadEspecialista::create([
                'doctor_id' => $doctor->id,
                'dia_id' => $dia_id,
                'hora_inicio_disponibilidad' => $request->hora_inicio,
                'hora_fin_disponibilidad' => $request->hora_fin,
            ]);
        }

        return redirect()->route('doctores.index')->with('success', 'Doctor y sus horarios registrados exitosamente.');
    }

    public function destroy($id)
    {
        // Al eliminar al doctor, Laravel se encarga de la relación si pusimos 'cascade'
        Doctor::destroy($id);
        return redirect()->route('doctores.index')->with('success', 'Doctor eliminado correctamente.');
    }
    // Método para mostrar el formulario con los datos cargados
    public function edit($id)
    {
        $doctor = Doctor::with('disponibilidad')->findOrFail($id);
        $especialidades = Especialidad::all();
        $dias = Dia::all();
        
        // Obtenemos los IDs de los días que el doctor ya tiene marcados
        $diasSeleccionados = $doctor->disponibilidad->pluck('dia_id')->toArray();

        return view('doctores.edit', compact('doctor', 'especialidades', 'dias', 'diasSeleccionados'));
    }

    // Método para procesar los cambios
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'dni_doctor' => 'required|unique:doctores,dni_doctor,' . $id,
            'cmp_doctor' => 'required|unique:doctores,cmp_doctor,' . $id,
            'dias' => 'required|array',
        ]);

        // 1. Actualizar datos básicos
        $doctor->update($request->only([
            'nombre_doctor', 'apellido_paterno_doctor', 'apellido_materno_doctor', 
            'dni_doctor', 'cmp_doctor', 'especialidad_id'
        ]));

        // 2. Actualizar Horarios (Borramos los anteriores y creamos los nuevos)
        $doctor->disponibilidad()->delete(); 

        foreach ($request->dias as $dia_id) {
            DisponibilidadEspecialista::create([
                'doctor_id' => $doctor->id,
                'dia_id' => $dia_id,
                'hora_inicio_disponibilidad' => $request->hora_inicio,
                'hora_fin_disponibilidad' => $request->hora_fin,
            ]);
        }

        return redirect()->route('doctores.index')->with('success', 'Información del médico actualizada.');
    }
}