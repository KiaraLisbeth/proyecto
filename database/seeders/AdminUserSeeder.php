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
            ['email' => 'admin@colegio.com'],
            [
                'nombre'   => 'Administrador',
                'apellido' => 'Sistema',
                'email'    => 'admin@colegio.com',
                'password' => Hash::make('Admin1234'),
                'rol'      => 'admin',
                'activo'   => true,
            ]
        );
    }
}
