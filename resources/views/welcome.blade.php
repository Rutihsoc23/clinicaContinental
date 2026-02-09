{{-- resources/views/welcome.blade.php --}}
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

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">CLÍNICA CONTINENTAL</a>
            <div class="d-flex">
                @auth
                    <span class="navbar-text text-white me-3">Hola, {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm">Cerrar Sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Iniciar Sesión</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="text-center mb-5 text-primary">SISTEMA DE GESTIÓN HOSPITALARIA</h1>

        @auth
            <div class="row g-4 justify-content-center">
                @if(Auth::user()->role === 'admin')
                <div class="col-md-4">
                    <div class="card h-100 shadow border-0 card-menu text-center" onclick="location.href='{{ route('doctores.index') }}'">
                        <div class="card-body py-5">
                            <h2 class="display-4">👨‍⚕️</h2>
                            <h3>DOCTORES</h3>
                            <p class="text-muted">Gestionar especialistas (Solo Admin).</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-md-4">
                    <div class="card h-100 shadow border-0 card-menu text-center" onclick="location.href='{{ route('pacientes.index') }}'">
                        <div class="card-body py-5">
                            <h2 class="display-4">👤</h2>
                            <h3>PACIENTES</h3>
                            <p class="text-muted">Registro de pacientes.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow border-0 card-menu text-center" onclick="location.href='{{ route('citas.create') }}'">
                        <div class="card-body py-5">
                            <h2 class="display-4">📅</h2>
                            <h3>AGENDAR CITA</h3>
                            <p class="text-muted">Nueva cita médica.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow border-0 card-menu text-center" onclick="location.href='{{ route('citas.index') }}'">
                        <div class="card-body py-5">
                            <h2 class="display-4">📊</h2>
                            <h3>REPORTES</h3>
                            <p class="text-muted">Ver historial.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center mt-5">
                <h3 class="text-muted">Bienvenido al Portal Interno</h3>
                <p class="lead">Por favor, inicie sesión para acceder a las herramientas de gestión.</p>
                <a href="{{ route('login') }}" class="btn btn-lg btn-primary mt-3 shadow">Ir al Login</a>
            </div>
        @endauth
    </div>
</body>
</html>
