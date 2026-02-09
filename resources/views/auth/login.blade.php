<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Clínica Continental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-login { border-radius: 15px; overflow: hidden; }
        .login-header { background: #0dcaf0; color: white; padding: 30px; text-align: center; }
        .btn-login { background-color: #0d6efd; border: none; padding: 10px; font-weight: bold; }
        .btn-login:hover { background-color: #0b5ed7; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg card-login border-0">
                    <div class="login-header">
                        <h2><i class="fas fa-hospital-user"></i></h2>
                        <h4 class="mb-0">Acceso al Sistema</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf

                            @if($errors->any())
                                <div class="alert alert-danger text-center py-2">
                                    <small>{{ $errors->first() }}</small>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label text-muted">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="ejemplo@clinica.com" value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-login shadow-sm">
                                INGRESAR
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-white py-3 border-0">
                        <small class="text-muted">Clínica Continental © 2026</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
