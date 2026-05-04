<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder principal. Ejecuta los seeders en el orden correcto
 * para respetar las dependencias entre tablas.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Primero los datos base (niveles, grados, secciones, cursos)
        $this->call(DatosBaseSeeder::class);

        // 2. Luego el usuario administrador
        $this->call(AdminUserSeeder::class);
    }
}
