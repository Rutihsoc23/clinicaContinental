@extends('layouts.app')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Reporte Global de Citas - Clínica Continental</h5>
        <a href="{{ route('citas.create') }}" class="btn btn-info btn-sm"> + Nueva Cita</a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped table-hover align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Paciente</th>
                    <th>Médico / Especialidad</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($citas as $cita)
                <tr>
                    <td>
                        <strong>{{ $cita->fecha_cita }}</strong><br>
                        <span class="text-muted">{{ $cita->hora_cita }}</span>
                    </td>

                    <td>
                        {{ $cita->paciente->nombre_paciente }} {{ $cita->paciente->apellido_paterno_paciente }}<br>
                        <small class="text-muted">DNI: {{ $cita->paciente->dni_paciente }}</small>
                    </td>

                    <td>
                        Dr. {{ $cita->doctor->nombre_doctor }} {{ $cita->doctor->apellido_paterno_doctor }}<br>
                        <span class="badge bg-outline-primary text-primary border border-primary">
                            {{ $cita->doctor->especialidad->nombre_especialidad }}
                        </span>
                    </td>

                    <td>
                        @if($cita->estado->nombre_estado == 'Pendiente')
                            <span class="badge bg-warning text-dark">{{ $cita->estado->nombre_estado }}</span>
                        @elseif($cita->estado->nombre_estado == 'Confirmada')
                            <span class="badge bg-success">{{ $cita->estado->nombre_estado }}</span>
                        @else
                            <span class="badge bg-danger">{{ $cita->estado->nombre_estado }}</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <form action="{{ route('citas.destroy', $cita->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Desea cancelar esta cita?')">
                                Cancelar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay citas programadas en la Clínica Continental.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection