@extends('layouts.app')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0"><i class="fas fa-users"></i> Listado General de Pacientes</h5>
        <a href="{{ route('pacientes.create') }}" class="btn btn-info btn-sm shadow-sm">
            <i class="fas fa-plus"></i> Registrar Nuevo
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>DNI</th>
                        <th>Paciente</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pacientes as $pac)
                    <tr>
                        <td class="fw-bold">{{ $pac->dni_paciente }}</td>
                        <td>{{ $pac->nombre_paciente }} {{ $pac->apellido_paterno_paciente }}</td>
                        <td>{{ $pac->telefono_paciente ?? '---' }}</td>
                        <td>{{ $pac->email_paciente }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                {{-- Botón que lleva a la edición real --}}
                                <a href="{{ route('pacientes.edit', $pac->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                {{-- Formulario para eliminar (opcional) --}}
                                <form action="{{ route('pacientes.destroy', $pac->id) }}" method="POST" onsubmit="return confirm('¿Eliminar registro?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
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
</div>
@endsection