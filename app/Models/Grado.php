<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo para los grados escolares (1ro, 2do, 3ro, etc.)
 * Cada grado pertenece a un nivel educativo.
 */
class Grado extends Model
{
    protected $table = 'grados';

    protected $fillable = ['nombre', 'nivel_id'];

    /**
     * Un grado pertenece a un nivel educativo.
     */
    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class);
    }

    /**
     * Un grado tiene muchas asignaciones de docentes.
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(DocenteAsignacion::class);
    }

    /**
     * Un grado tiene muchos archivos subidos.
     */
    public function archivos(): HasMany
    {
        return $this->hasMany(Archivo::class);
    }
}
