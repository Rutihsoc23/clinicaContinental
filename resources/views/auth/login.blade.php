<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Clínica Continental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-login { border-radius: 15px; overflow: hidden; }
        .login-header { background: #0dcaf0; color: white; padding: 30px; text-align: center; }
        .btn-login { background-color: #0d6efd; border: none; padding: 10px; font-weight: bold; }
        .btn-login:hover { background-color: #0b5ed7; }
        
        /* Estilo extra para cuando los inputs estén bloqueados */
        .form-control:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
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
                        
                        <div id="countdown-alert" class="alert alert-danger text-center shadow-sm border-danger" style="display: none;">
                            <i class="fas fa-lock me-1"></i> <strong>SISTEMA BLOQUEADO</strong><br>
                            <span class="small">Intente nuevamente en <strong id="timer-number" class="fs-5">0</strong> segundos.</span>
                        </div>

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf

                            @if($errors->any())
                                <div class="alert alert-danger text-center py-2 shadow-sm">
                                    <small><i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}</small>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label text-muted">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-secondary"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" 
                                           placeholder="ejemplo@clinica.com" value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-secondary"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" 
                                           placeholder="••••••••" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-login shadow-sm transition">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Capturamos la variable enviada desde el controlador AuthController.php
            // Si no existe la variable, el valor por defecto es 0.
            let timeLeft = {{ session('lockout_time', 0) }};

            // Solo ejecutamos el bloqueo si hay tiempo pendiente (> 0)
            if (timeLeft > 0) {
                activarModoBloqueo(timeLeft);
            }

            function activarModoBloqueo(seconds) {
                // Referencias a los elementos del DOM
                const alertBox = document.getElementById('countdown-alert');
                const timerDisplay = document.getElementById('timer-number');
                const emailInput = document.querySelector('input[name="email"]');
                const passwordInput = document.querySelector('input[name="password"]');
                const submitBtn = document.querySelector('button[type="submit"]');

                // A. Bloquear Visualmente
                emailInput.disabled = true;
                passwordInput.disabled = true;
                submitBtn.disabled = true;
                
                // Guardar el texto original del botón para restaurarlo luego
                const textoOriginal = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-ban"></i> ESPERE...';
                submitBtn.classList.add('btn-secondary'); // Cambiar color a gris
                submitBtn.classList.remove('btn-primary');

                // B. Mostrar Alerta
                alertBox.style.display = 'block';
                timerDisplay.innerText = seconds;

                // C. Iniciar Cuenta Regresiva
                const interval = setInterval(() => {
                    seconds--;
                    timerDisplay.innerText = seconds;

                    // Si el tiempo se acaba...
                    if (seconds <= 0) {
                        clearInterval(interval);
                        
                        // D. Desbloquear Todo
                        emailInput.disabled = false;
                        passwordInput.disabled = false;
                        submitBtn.disabled = false;
                        
                        // Restaurar botón
                        submitBtn.innerHTML = textoOriginal;
                        submitBtn.classList.add('btn-primary');
                        submitBtn.classList.remove('btn-secondary');

                        // Ocultar alerta
                        alertBox.style.display = 'none';
                        
                        // Poner foco en el email para facilitar reintento
                        emailInput.focus();
                    }
                }, 1000); // Se repite cada 1 segundo (1000 ms)
            }
        });
    </script>
</body>
</html>