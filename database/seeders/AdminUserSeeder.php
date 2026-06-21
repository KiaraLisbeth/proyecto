<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder para crear el usuario administrador por defecto.
 * Credenciales: admin@colegio.com / Admin1234
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'ADMIN'],
            [
                'nombre'         => 'Administrador',
                'apellido'       => 'Sistema',
                'username'       => 'ADMIN',
                'email'          => 'admin@sistema.local',
                'password'       => Hash::make('Admin1234'),
                'password_plain' => 'Admin1234',
                'rol'            => 'admin',
                'activo'         => true,
            ]
        );
    }
}
