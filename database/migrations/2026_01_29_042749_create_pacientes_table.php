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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_paciente', 100);
            $table->string('apellido_paterno_paciente', 100);
            $table->string('apellido_materno_paciente', 100);
            $table->char('dni_paciente', 8)->unique();
            $table->string('telefono_paciente', 15)->nullable();
            $table->string('email_paciente')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
