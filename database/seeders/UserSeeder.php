<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@clinica.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Usuario Recepcionista
        User::create([
            'name' => 'Recepcionista',
            'email' => 'recepcion@clinica.com',
            'password' => Hash::make('recepcion123'),
            'role' => 'recepcionista'
        ]);
    }
}
