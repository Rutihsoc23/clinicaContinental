@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Editar Especialista: {{ $doctor->nombre_doctor }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('doctores.update', $doctor->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- ¡Importante! Laravel necesita esto para actualizar --}}

                <h5 class="text-primary">Datos del Médico</h5>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label>DNI</label>
                        <input type="text" name="dni_doctor" class="form-control" value="{{ $doctor->dni_doctor }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>CMP</label>
                        <input type="text" name="cmp_doctor" class="form-control" value="{{ $doctor->cmp_doctor }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Especialidad</label>
                        <select name="especialidad_id" class="form-control" required>
                            @foreach($especialidades as $esp)
                                <option value="{{ $esp->id }}" {{ $doctor->especialidad_id == $esp->id ? 'selected' : '' }}>
                                    {{ $esp->nombre_especialidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre_doctor" class="form-control" value="{{ $doctor->nombre_doctor }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Ap. Paterno</label>
                        <input type="text" name="apellido_paterno_doctor" class="form-control" value="{{ $doctor->apellido_paterno_doctor }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Ap. Materno</label>
                        <input type="text" name="apellido_materno_doctor" class="form-control" value="{{ $doctor->apellido_materno_doctor }}" required>
                    </div>
                </div>

                <hr>
                <h5 class="text-primary">Días de Trabajo (Interdiario o fines de semana)</h5>
                <div class="row mb-3">
                    @foreach($dias as $dia)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="{{ $dia->id }}" 
                                id="dia{{ $dia->id }}" {{ in_array($dia->id, $diasSeleccionados) ? 'checked' : '' }}>
                            <label class="form-check-label" for="dia{{ $dia->id }}">{{ $dia->nombre_dia }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Hora Inicio</label>
                        <input type="time" name="hora_inicio" class="form-control" 
                            value="{{ $doctor->disponibilidad->first()->hora_inicio_disponibilidad ?? '' }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Hora Fin</label>
                        <input type="time" name="hora_fin" class="form-control" 
                            value="{{ $doctor->disponibilidad->first()->hora_fin_disponibilidad ?? '' }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning px-5">Actualizar Médico</button>
                    <a href="{{ route('doctores.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection