<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para los años lectivos del sistema.
 * Solo un año puede estar activo a la vez.
 */
class AnioLectivo extends Model
{
    protected $table = 'anio_lectivos';

    protected $fillable = ['anio', 'activo', 'fecha_inicio'];

    protected $casts = ['activo' => 'boolean', 'fecha_inicio' => 'date'];

    /**
     * Retorna el año lectivo activo actualmente.
     */
    public static function actual(): ?self
    {
        return static::where('activo', true)->first();
    }

    /**
     * Cuenta los archivos asociados a este año.
     */
    public function totalArchivos(): int
    {
        return Archivo::where('anio', $this->anio)->count();
    }
}
