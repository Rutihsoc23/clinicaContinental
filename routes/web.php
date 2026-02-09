<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\HistoriaClinicaController;

// El ->name('home') es lo que permite usar route('home') en el HTML
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Módulo de Doctores (Incluye: Listar, Agregar, Editar, Eliminar)
Route::resource('doctores', DoctorController::class);

// 3. Módulo de Pacientes (Incluye: Listar, Agregar, Editar, Eliminar)
Route::resource('pacientes', PacienteController::class);


// Cambiamos las rutas anteriores por esta que incluye todo (index, create, store, destroy)
Route::resource('citas', CitaController::class);

// Rutas para historia clínica
Route::get('citas/{cita}/historia/create', [HistoriaClinicaController::class, 'create'])->name('historias.create');
Route::post('historias', [HistoriaClinicaController::class, 'store'])->name('historias.store');
