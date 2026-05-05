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
}
