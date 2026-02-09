@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-warning py-3">
                <h5 class="mb-0 text-dark"><i class="fas fa-user-edit"></i> Actualizar Registro de Paciente</h5>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">DNI</label>
                            <input type="text" name="dni_paciente" class="form-control" value="{{ old('dni_paciente', $paciente->dni_paciente) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Teléfono</label>
                            <input type="text" name="telefono_paciente" class="form-control" value="{{ old('telefono_paciente', $paciente->telefono_paciente) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label font-weight-bold">Nombre</label>
                            <input type="text" name="nombre_paciente" class="form-control" value="{{ old('nombre_paciente', $paciente->nombre_paciente) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno_paciente" class="form-control" value="{{ old('apellido_paterno_paciente', $paciente->apellido_paterno_paciente) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Apellido Materno</label>
                            <input type="text" name="apellido_materno_paciente" class="form-control" value="{{ old('apellido_materno_paciente', $paciente->apellido_materno_paciente) }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label font-weight-bold">Correo Electrónico</label>
                            <input type="email" name="email_paciente" class="form-control" value="{{ old('email_paciente', $paciente->email_paciente) }}" required>
                            <small class="text-muted">Ejemplo: paciente@correo.com</small>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('pacientes.index') }}" class="btn btn-secondary px-4">Volver</a>
                        <button type="submit" class="btn btn-warning px-5 shadow-sm">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection