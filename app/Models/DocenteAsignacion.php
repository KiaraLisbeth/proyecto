<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para las asignaciones de docentes.
 * Cada registro indica que un docente dicta un curso específico
 * en un grado y sección determinados.
 */
class DocenteAsignacion extends Model
{
    protected $table = 'docente_asignaciones';

    protected $fillable = [
        'user_id',
        'curso_id',
        'grado_id',
        'seccion_id',
    ];

    /**
     * La asignación pertenece a un docente (usuario).
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * La asignación pertenece a un curso.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * La asignación pertenece a un grado.
     */
    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }

    /**
     * La asignación pertenece a una sección.
     */
    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }
}
