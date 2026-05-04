<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migración para la tabla de archivos subidos por los docentes
// Los archivos se almacenan en storage/app/public accesibles vía storage:link
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');      // Docente que subió el archivo
            $table->string('nombre_original');                 // Nombre original del archivo
            $table->string('nombre_archivo');                  // Nombre generado para almacenamiento
            $table->string('ruta');                            // Ruta relativa en storage
            $table->string('tipo_archivo');                    // Mime type del archivo
            $table->unsignedBigInteger('tamanio');             // Tamaño en bytes
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');     // Curso al que pertenece el archivo
            $table->foreignId('grado_id')
                  ->constrained('grados')
                  ->onDelete('cascade');     // Grado al que pertenece
            $table->foreignId('seccion_id')
                  ->constrained('secciones')
                  ->onDelete('cascade');    // Sección a la que pertenece
            $table->text('descripcion')->nullable();            // Descripción opcional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivos');
    }
};
