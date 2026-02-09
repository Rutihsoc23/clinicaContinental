<h1>Reporte Diario de Citas</h1>
<p>Total Citas: {{ $totalCitas }}</p>

<ul>
    @foreach($citasDelDia as $cita)
        <li>{{ $cita->hora_cita }} - {{ $cita->paciente->nombre_paciente }}</li>
    @endforeach
</ul>
