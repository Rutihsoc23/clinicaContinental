<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_doctor', 100);
            $table->string('apellido_paterno_doctor', 100);
            $table->string('apellido_materno_doctor', 100);
            $table->char('dni_doctor', 8)->unique();
            $table->string('cmp_doctor', 10)->unique();
            $table->foreignId('especialidad_id')->constrained('especialidades'); // El distintivo aquí es la relación
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
