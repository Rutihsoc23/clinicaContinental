@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Registrar Doctor y Horarios Multi-Día</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('doctores.store') }}" method="POST">
                @csrf
                <h5 class="text-primary">Datos del Médico</h5>
                <div class="row">
                    <div class="col-md-4 mb-3"><label>DNI</label><input type="text" name="dni_doctor" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>CMP</label><input type="text" name="cmp_doctor" class="form-control" required></div>
                    <div class="col-md-4 mb-3">
                        <label>Especialidad</label>
                        <select name="especialidad_id" class="form-control" required>
                            @foreach($especialidades as $esp)
                                <option value="{{ $esp->id }}">{{ $esp->nombre_especialidad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3"><label>Nombre</label><input type="text" name="nombre_doctor" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Ap. Paterno</label><input type="text" name="apellido_paterno_doctor" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Ap. Materno</label><input type="text" name="apellido_materno_doctor" class="form-control" required></div>
                </div>

                <hr>
                <h5 class="text-primary">Días de Trabajo (Puedes marcar varios)</h5>
                <div class="row mb-3">
                    @foreach($dias as $dia)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="{{ $dia->id }}" id="dia{{ $dia->id }}">
                            <label class="form-check-label" for="dia{{ $dia->id }}">{{ $dia->nombre_dia }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3"><label>Hora Inicio</label><input type="time" name="hora_inicio" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>Hora Fin</label><input type="time" name="hora_fin" class="form-control" required></div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Guardar Doctor y Horarios</button>
            </form>
        </div>
    </div>
</div>
@endsection 