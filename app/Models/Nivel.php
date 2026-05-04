<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo para los niveles educativos: Inicial, Primaria, Secundaria.
 */
class Nivel extends Model
{
    protected $table = 'niveles';

    protected $fillable = ['nombre'];

    /**
     * Un nivel tiene muchos grados (1ro, 2do, 3ro, etc.)
     */
    public function grados(): HasMany
    {
        return $this->hasMany(Grado::class);
    }
}
