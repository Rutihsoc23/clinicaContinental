<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  El rol requerido para pasar
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Verificar si está logueado
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Verificar si tiene el rol correcto
        // Si el usuario es 'admin', lo dejamos pasar a todo (Superpoder)
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Si su rol no coincide con el requerido, prohibido (403)
        if (Auth::user()->role !== $role) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
