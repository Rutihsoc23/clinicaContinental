<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\HistoriaClinica;
use Illuminate\Http\Request;

class HistoriaClinicaController extends Controller
{
    public function create($cita_id)
    {
        $cita = Cita::findOrFail($cita_id);

        // --- VALIDACIÓN RF05/RF07 ---
        // Si la cita NO está atendida (ID 2), abortamos con error 403
        if ($cita->estado_id != 2) {
            abort(403, 'No se puede crear historia clínica de una cita no atendida.');
        }

        // Retornamos la vista (que tendrías que crear en resources/views/historias/create.blade.php)
        // Para que el test pase, basta con que el método exista y retorne una vista, aunque la vista esté vacía por ahora.
        return view('historias.create', compact('cita'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'diagnostico' => 'required',
            'tratamiento' => 'required'
        ]);

        // Doble check de seguridad
        $cita = Cita::findOrFail($request->cita_id);
        if ($cita->estado_id != 2) {
             abort(403, 'Acción no autorizada.');
        }

        HistoriaClinica::create($request->all());

        return redirect()->route('citas.index')->with('success', 'Historia guardada.');
    }
}
