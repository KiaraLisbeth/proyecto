<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migración para asignaciones de docentes: un docente puede tener múltiples asignaciones
// (curso + grado + sección) definiendo en qué combinaciones dicta clases
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docente_asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');    // Docente asignado
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');   // Curso que dicta
            $table->foreignId('grado_id')
                  ->constrained('grados')
                  ->onDelete('cascade');   // Grado al que dicta
            $table->foreignId('seccion_id')
                  ->constrained('secciones')
                  ->onDelete('cascade');   // Sección a la que dicta
            $table->timestamps();

            // Evitar asignaciones duplicadas para el mismo docente
            $table->unique(['user_id', 'curso_id', 'grado_id', 'seccion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente_asignaciones');
    }
};
