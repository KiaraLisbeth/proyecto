<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Modelo para los archivos subidos por los docentes.
 * Los archivos se almacenan en storage/app/public.
 */
class Archivo extends Model
{
    use SoftDeletes;

    protected $table = 'archivos';

    protected $fillable = [
        'user_id',
        'nombre_original',
        'nombre_archivo',
        'ruta',
        'tipo_archivo',
        'tamanio',
        'curso_id',
        'grado_id',
        'seccion_id',
        'bimestre',
        'anio',
        'descripcion',
    ];

    /**
     * El archivo fue subido por un docente (usuario).
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * El archivo pertenece a un curso.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * El archivo pertenece a un grado.
     */
    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }

    /**
     * El archivo pertenece a una sección.
     */
    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    /**
     * Retorna el tamaño del archivo formateado en KB o MB.
     */
    public function getTamanioFormateadoAttribute(): string
    {
        $bytes = $this->tamanio;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        return number_format($bytes / 1024, 2) . ' KB';
    }

    /**
     * Retorna la URL pública del archivo almacenado.
     */
    public function getUrlPublicaAttribute(): string
    {
        return Storage::url($this->ruta);
    }

    /**
     * Retorna el nombre del bimestre en formato romano.
     * Ej: 1 => 'I Bimestre', 2 => 'II Bimestre'
     */
    public function getBimestreNombreAttribute(): string
    {
        return match ((int) $this->bimestre) {
            1 => 'I Bimestre',
            2 => 'II Bimestre',
            3 => 'III Bimestre',
            4 => 'IV Bimestre',
            default => 'Sin Bimestre',
        };
    }

    /**
     * Lista de bimestres disponibles (para vistas y formularios).
     * El valor 0 representa "Todos los Bimestres" (reporte consolidado).
     */
    public static function bimestres(): array
    {
        return [
            0 => 'Todos los Bimestres',
            1 => 'I Bimestre',
            2 => 'II Bimestre',
            3 => 'III Bimestre',
            4 => 'IV Bimestre',
        ];
    }

    /**
     * Retorna los años lectivos disponibles (desde 2026 hasta el año actual).
     * Usado para el selector de filtro en vistas.
     */
    public static function aniosDisponibles(): array
    {
        $anioInicio = 2026;
        $anioActual = (int) now()->year;
        $anios = [];
        for ($a = $anioActual; $a >= $anioInicio; $a--) {
            $anios[$a] = "Año Lectivo $a";
        }
        return $anios;
    }
}
