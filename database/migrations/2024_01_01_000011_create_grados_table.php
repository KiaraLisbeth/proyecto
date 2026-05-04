<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migración para la tabla de grados, asociados a un nivel educativo
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');                          // Ej: 1ro, 2do, 3ro
            $table->foreignId('nivel_id')
                  ->constrained('niveles')
                  ->onDelete('cascade'); // Nivel al que pertenece el grado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grados');
    }
};
