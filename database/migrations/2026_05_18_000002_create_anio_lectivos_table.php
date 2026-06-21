<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Tabla para gestionar los años lectivos del sistema.
 * Permite archivar años anteriores y crear nuevos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anio_lectivos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('anio')->unsigned()->unique();
            $table->boolean('activo')->default(false);   // Solo uno puede estar activo
            $table->date('fecha_inicio')->nullable();
            $table->timestamps();
        });

        // Insertar el año 2026 como activo por defecto
        DB::table('anio_lectivos')->insert([
            'anio'         => 2026,
            'activo'       => true,
            'fecha_inicio' => '2026-01-03',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('anio_lectivos');
    }
};
