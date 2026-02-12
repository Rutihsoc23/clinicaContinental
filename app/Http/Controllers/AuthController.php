<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Llave única por IP + Email
        $throttleKey = 'login:' . $request->ip() . '|' . $request->input('email');

        // 1. VERIFICACIÓN INICIAL: ¿Ya estaba bloqueado de antes?
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "Sistema bloqueado. Espere $seconds segundos."])
                ->with('lockout_time', $seconds);
        }

        // 2. INTENTO DE LOGIN
        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey); // Éxito: borrar contador
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        // 3. FALLO: Sumamos el intento y bloqueamos por 30 segundos si corresponde
        RateLimiter::hit($throttleKey, 30);

        // --- EL CAMBIO CLAVE ESTÁ AQUÍ ---
        // Verificamos INMEDIATAMENTE si este fue el 3er fallo
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            // ¡Boom! Devolvemos el bloqueo AHORA MISMO, sin esperar al 4to intento
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Ha superado el número de intentos permitidos.'])
                ->with('lockout_time', $seconds); // Esto activa tu JS
        }

        // Si es el fallo 1 o 2, solo mostramos error de credenciales
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}