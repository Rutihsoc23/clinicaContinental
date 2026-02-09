<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function diario()
    {
        // Obtenemos la fecha de hoy
        $hoy = Carbon::now()->format('Y-m-d');

        // Filtramos las citas de HOY
        $citasDelDia = Cita::with(['doctor', 'paciente', 'estado'])
                            ->whereDate('fecha_cita', $hoy)
                            ->get();

        // Calculamos totales simples
        $totalCitas = $citasDelDia->count();

        return view('reportes.diario', compact('citasDelDia', 'totalCitas'));
    }
}
