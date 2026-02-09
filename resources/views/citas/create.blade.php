@extends('layouts.app')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0"><i class="fas fa-calendar-plus"></i> Agendar Nueva Cita Médica</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('citas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold">1. Seleccionar Paciente</label>
                    <select name="paciente_id" class="form-select select2" required>
                        <option value="">Escriba DNI o Nombre del paciente...</option>
                        @foreach($pacientes as $pac)
                            <option value="{{ $pac->id }}">
                                {{ $pac->dni_paciente }} - {{ $pac->nombre_paciente }} {{ $pac->apellido_paterno_paciente }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold">2. Seleccionar Especialista y Ver Horarios</label>
                    <select name="doctor_id" class="form-select" required>
                        <option value="">Seleccione Doctor...</option>
                        @foreach($doctores as $doc)
                            <option value="{{ $doc->id }}">
                                {{ $doc->nombre_doctor }} ({{ $doc->especialidad->nombre_especialidad }}) 
                                | Disp: 
                                @forelse($doc->disponibilidad as $disp)
                                    {{ $disp->dia->nombre_dia }} ({{ \Carbon\Carbon::parse($disp->hora_inicio_disponibilidad)->format('H:i') }} - {{ \Carbon\Carbon::parse($disp->hora_fin_disponibilidad)->format('H:i') }}){{ !$loop->last ? ' / ' : '' }}
                                @empty
                                    Sin horario asignado
                                @endforelse
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Se muestran todos los turnos configurados para el especialista.</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha de Cita</label>
                    <input type="date" name="fecha_cita" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Hora</label>
                    <input type="time" name="hora_cita" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado_id" class="form-select">
                        @foreach($estados as $est)
                            <option value="{{ $est->id }}">{{ $est->nombre_estado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-info w-100 mt-3 shadow-sm text-white font-weight-bold">
                <i class="fas fa-save"></i> FINALIZAR AGENDAMIENTO
            </button>
        </form>
    </div>
</div>
@endsection