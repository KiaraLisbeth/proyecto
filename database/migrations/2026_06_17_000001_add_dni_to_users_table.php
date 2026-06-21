<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega el campo DNI a la tabla users.
     * El DNI es único para evitar registrar al mismo docente con diferentes usuarios.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 8)->nullable()->unique()->after('apellido');
        });
    }

    /**
     * Elimina el campo DNI de la tabla users.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['dni']);
            $table->dropColumn('dni');
        });
    }
};
