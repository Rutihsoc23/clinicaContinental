<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    // Listar todos los pacientes
    public function index() {
        $pacientes = Paciente::all(); // Envías una LISTA (plural)
        return view('pacientes.index', compact('pacientes'));
    }

    // Formulario de creación (ya lo tienes, pero asegúrate de que esté aquí)
    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni_paciente' => 'required|unique:pacientes,dni_paciente',
            'email_paciente' => 'required|email|unique:pacientes,email_paciente',
        ]);

        Paciente::create($request->all());
        return redirect()->route('pacientes.index')->with('success', 'Paciente registrado.');
    }

    // Formulario de edición
    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        
        return view('pacientes.edit', compact('paciente'));
    }

    // Actualizar datos
    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        // 1. Validación (Ojo: ignoramos el DNI del paciente actual para que no diga "ya existe")
        $request->validate([
            'dni_paciente' => 'required|unique:pacientes,dni_paciente,' . $id,
            'nombre_paciente' => 'required',
            'email_paciente' => 'required|email|unique:pacientes,email_paciente,' . $id,
        ]);

        // 2. GUARDAR (Esta es la línea que suele faltar)
        $paciente->update($request->all());

        // 3. Redirigir con mensaje de éxito
        return redirect()->route('pacientes.index')
                        ->with('success', '¡Datos actualizados correctamente!');
    }

    // Eliminar registro
    public function destroy($id)
    {
        Paciente::destroy($id);
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado.');
    }

}