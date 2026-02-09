<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Especialidad;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_recepcionista_no_puede_acceder_a_gestion_de_doctores()
    {
        // 1. Crear un usuario con rol 'recepcionista'
        $recepcionista = User::factory()->create([
            'role' => 'recepcionista',
            'email' => 'recepcion@clinica.com'
        ]);

        // 2. Actuar como este usuario (Login simulado)
        $response = $this->actingAs($recepcionista)
                         ->get(route('doctores.index'));

        // 3. Aserción: Esperamos un error 403 (Forbidden)
        $response->assertStatus(403);
    }

    /** @test */
    public function un_administrador_si_puede_acceder_a_gestion_de_doctores()
    {
        // 1. Crear usuario Admin
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@clinica.com'
        ]);

        // Necesitamos datos para que la vista index no falle al cargar
        Especialidad::create(['nombre_especialidad' => 'General']);

        // 2. Actuar como Admin
        $response = $this->actingAs($admin)
                         ->get(route('doctores.index'));

        // 3. Aserción: Esperamos éxito (200 OK)
        $response->assertStatus(200);
    }

    /** @test */
    public function un_usuario_anonimo_es_redirigido_al_login()
    {
        // Intentar entrar sin hacer login
        $response = $this->get(route('doctores.index'));

        // Debe redirigir al login (302)
        $response->assertRedirect(route('login')); // O '/login' si no tienes nombre de ruta
    }
}
