<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Clínica Continental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-menu { transition: 0.3s; cursor: pointer; }
        .card-menu:hover { transform: translateY(-10px); background-color: #f8f9fa; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="text-center mb-5 text-primary">SISTEMA DE GESTIÓN - CLÍNICA CONTINENTAL</h1>
        
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card h-100 shadow border-0 card-menu text-center" onclick="location.href='{{ route('doctores.index') }}'">
                    <div class="card-body py-5">
                        <h2 class="display-4">👨‍⚕️</h2>
                        <h3>DOCTORES</h3>
                        <p class="text-muted">Gestionar especialistas, horarios y especialidades.</p>
                        <button class="btn btn-outline-primary">Entrar al Módulo</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow border-0 card-menu text-center" onclick="location.href='{{ route('pacientes.index') }}'">
                    <div class="card-body py-5">
                        <h2 class="display-4">👤</h2>
                        <h3>PACIENTES</h3>
                        <p class="text-muted">Registro, edición y perfiles de pacientes.</p>
                        <button class="btn btn-outline-success">Entrar al Módulo</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow border-0 card-menu text-center" onclick="location.href='{{ route('citas.create') }}'">
                    <div class="card-body py-5">
                        <h2 class="display-4">📅</h2>
                        <h3>AGENDAR CITA</h3>
                        <p class="text-muted">Vincular paciente con doctor y horario disponible.</p>
                        <button class="btn btn-outline-info text-dark">Abrir Agenda</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow border-0 card-menu text-center" onclick="location.href='{{ route('citas.index') }}'">
                    <div class="card-body py-5">
                        <h2 class="display-4">📊</h2>
                        <h3>REPORTE GLOBAL</h3>
                        <p class="text-muted">Ver todas las citas, estados y médicos asignados.</p>
                        <button class="btn btn-outline-dark">Ver Reporte</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>