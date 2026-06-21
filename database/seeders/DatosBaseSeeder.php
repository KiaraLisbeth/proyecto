<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\Curso;

/**
 * Seeder para los datos base del sistema:
 * Niveles, Grados por nivel, Secciones y Cursos.
 */
class DatosBaseSeeder extends Seeder
{
    public function run(): void
    {
        // ──────────────────────────────────────────────────────────
        // 1. NIVELES EDUCATIVOS (valores fijos del sistema)
        // ──────────────────────────────────────────────────────────
        $inicial    = Nivel::firstOrCreate(['nombre' => 'Inicial']);
        $primaria   = Nivel::firstOrCreate(['nombre' => 'Primaria']);
        $secundaria = Nivel::firstOrCreate(['nombre' => 'Secundaria']);

        // ──────────────────────────────────────────────────────────
        // 2. GRADOS por nivel
        // ──────────────────────────────────────────────────────────

        // Inicial: 3, 4 y 5 años
        foreach (['3 años', '4 años', '5 años'] as $nombre) {
            Grado::firstOrCreate(['nombre' => $nombre, 'nivel_id' => $inicial->id]);
        }

        // Primaria: 1ro a 6to
        foreach (['1ro', '2do', '3ro', '4to', '5to', '6to'] as $nombre) {
            Grado::firstOrCreate(['nombre' => $nombre, 'nivel_id' => $primaria->id]);
        }

        // Secundaria: 1ro a 5to
        foreach (['1ro', '2do', '3ro', '4to', '5to'] as $nombre) {
            Grado::firstOrCreate(['nombre' => $nombre, 'nivel_id' => $secundaria->id]);
        }

        // ──────────────────────────────────────────────────────────
        // 3. SECCIONES  (institución con sección única por grado)
        // ──────────────────────────────────────────────────────────
        Seccion::firstOrCreate(['nombre' => 'Única']);

        // ──────────────────────────────────────────────────────────
        // 4. CURSOS
        // ──────────────────────────────────────────────────────────
        $cursos = [
            'Matemática',
            'Comunicación',
            'Ciencias Naturales',
            'Historia, Geografía y Economía',
            'Inglés',
            'Arte y Cultura',
            'Educación Física',
            'Educación Religiosa',
            'Personal Social',
            'Computación',
            'Tutoría',
            'Ciencia y Tecnología',
            'Educación Cristiana',
            'Álgebra',
            'Geometría',
            'Literatura',
            'Razonamiento Matemático',
            'Razonamiento Verbal',
        ];

        foreach ($cursos as $curso) {
            Curso::firstOrCreate(['nombre' => $curso]);
        }
    }
}
