<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\ReporteController;

// El ->name('home') es lo que permite usar route('home') en el HTML
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas para historia clínica
Route::get('citas/{cita}/historia/create', [HistoriaClinicaController::class, 'create'])->name('historias.create');
Route::post('historias', [HistoriaClinicaController::class, 'store'])->name('historias.store');

// --- GRUPO PROTEGIDO: SOLO ADMINISTRADORES ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Aquí movemos la gestión de doctores
    Route::resource('doctores', DoctorController::class);
    Route::get('reportes/diario', [ReporteController::class, 'diario'])->name('reportes.diario');
});

// --- GRUPO PROTEGIDO: RECEPCIONISTAS Y ADMINS ---
// (Podrías crear lógica para permitir múltiples roles, pero por ahora simplifiquemos)
Route::middleware(['auth'])->group(function () {
    Route::resource('pacientes', PacienteController::class);
    Route::resource('citas', CitaController::class);
});

// Ruta dummy para que el test no falle al redirigir si no estás logueado
Route::get('/login', function () {
    return 'Por favor inicia sesión';
})->name('login');
