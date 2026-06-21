<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Agrega la columna 'anio' a la tabla archivos para archivar
 * documentos por año lectivo (2026, 2027, etc.).
 * Los registros existentes se marcan con el año 2026 por defecto.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archivos', function (Blueprint $table) {
            $table->smallInteger('anio')->unsigned()->default(2026)->after('bimestre');
        });

        // Asignar año 2026 a todos los registros existentes
        DB::table('archivos')->update(['anio' => 2026]);
    }

    public function down(): void
    {
        Schema::table('archivos', function (Blueprint $table) {
            $table->dropColumn('anio');
        });
    }
};
