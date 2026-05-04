<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo para las secciones escolares (A, B, C, etc.)
 */
class Seccion extends Model
{
    protected $table = 'secciones';

    protected $fillable = ['nombre'];

    /**
     * Una sección tiene muchas asignaciones de docentes.
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(DocenteAsignacion::class);
    }

    /**
     * Una sección tiene muchos archivos subidos.
     */
    public function archivos(): HasMany
    {
        return $this->hasMany(Archivo::class);
    }
}
