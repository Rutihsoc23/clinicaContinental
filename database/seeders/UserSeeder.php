<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Admin: Solo se crea si el email no existe
        User::firstOrCreate(
            ['email' => 'admin@clinica.com'], // Condición de búsqueda
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        // Usuario Recepcionista: Solo se crea si el email no existe
        User::firstOrCreate(
            ['email' => 'recepcion@clinica.com'], // Condición de búsqueda
            [
                'name' => 'Recepcionista',
                'password' => Hash::make('recepcion123'),
                'role' => 'recepcionista'
            ]
        );
    }
}
