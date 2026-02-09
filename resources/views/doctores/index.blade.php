@extends('layouts.app')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Especialistas - Clínica Continental</h5>
        <a href="{{ route('doctores.create') }}" class="btn btn-light btn-sm font-weight-bold">+ Nuevo Doctor</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Especialista</th>
                    <th>DNI / CMP</th>
                    <th>Especialidad</th>
                    <th>Horario de Atención</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctores as $doc)
                <tr>
                    <td>
                        <strong>{{ $doc->nombre_doctor }} {{ $doc->apellido_paterno_doctor }}</strong>
                    </td>
                    <td>
                        <small class="text-muted">DNI: {{ $doc->dni_doctor }}</small><br>
                        <span class="badge bg-info text-dark">{{ $doc->cmp_doctor }}</span>
                    </td>
                    <td>{{ $doc->especialidad->nombre_especialidad }}</td>
                    <td>
                        @foreach($doc->disponibilidad as $disp)
                            <span class="badge bg-secondary">
                                {{ $disp->dia->nombre_dia }}: {{ $disp->hora_inicio_disponibilidad }} - {{ $disp->hora_fin_disponibilidad }}
                            </span>
                        @endforeach
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('doctores.edit', $doc->id) }}" class="btn btn-warning btn-sm">
                                📝 Editar
                            </a>

                            <form action="{{ route('doctores.destroy', $doc->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('¿Estás seguro de eliminar al Dr. {{ $doc->nombre_doctor }}? Esta acción no se puede deshacer.')">
                                    🗑️ Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection