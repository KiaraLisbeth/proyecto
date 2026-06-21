<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'username',
        'email',
        'password',
        'password_plain',
        'rol',
        'activo',
    ];

    /**
     * Los atributos ocultos para serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversiones de tipos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'activo'            => 'boolean',
        ];
    }

    /**
     * Retorna el nombre completo del usuario.
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Verifica si el usuario es administrador.
     */
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Verifica si el usuario es docente.
     */
    public function esDocente(): bool
    {
        return $this->rol === 'docente';
    }

    /**
     * Un usuario (docente) tiene muchas asignaciones de cursos/grados/secciones.
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(DocenteAsignacion::class);
    }

    /**
     * Un usuario (docente) ha subido muchos archivos.
     */
    public function archivos(): HasMany
    {
        return $this->hasMany(Archivo::class);
    }
}
