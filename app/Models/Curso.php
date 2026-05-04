<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo para los cursos escolares (Matemática, Comunicación, Ciencias, etc.)
 */
class Curso extends Model
{
    protected $table = 'cursos';

    protected $fillable = ['nombre'];

    /**
     * Un curso tiene muchas asignaciones de docentes.
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(DocenteAsignacion::class);
    }

    /**
     * Un curso tiene muchos archivos subidos.
     */
    public function archivos(): HasMany
    {
        return $this->hasMany(Archivo::class);
    }
}
