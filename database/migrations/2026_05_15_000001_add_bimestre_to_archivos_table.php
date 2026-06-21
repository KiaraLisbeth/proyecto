<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega el campo 'bimestre' a la tabla de archivos.
 * Valores: 1 = I Bimestre, 2 = II Bimestre, 3 = III Bimestre, 4 = IV Bimestre
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archivos', function (Blueprint $table) {
            $table->tinyInteger('bimestre')
                  ->unsigned()
                  ->default(1)
                  ->after('seccion_id')
                  ->comment('1=I Bimestre, 2=II, 3=III, 4=IV');
        });
    }

    public function down(): void
    {
        Schema::table('archivos', function (Blueprint $table) {
            $table->dropColumn('bimestre');
        });
    }
};
