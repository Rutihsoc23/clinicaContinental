<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Paciente - Clínica Continental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Nuevo Registro de Paciente</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pacientes.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Nombres</label>
                                    <input type="text" name="nombre_paciente" class="form-control" value="{{ old('nombre_paciente') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Apellido Paterno</label>
                                    <input type="text" name="apellido_paterno_paciente" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Apellido Materno</label>
                                    <input type="text" name="apellido_materno_paciente" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">DNI (8 dígitos)</label>
                                    <input type="text" name="dni_paciente" class="form-control" maxlength="8" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" name="telefono_paciente" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" name="email_paciente" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Registrar y Continuar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>