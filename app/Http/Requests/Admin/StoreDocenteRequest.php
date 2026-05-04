<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest para validar la creación de un nuevo docente.
 * Incluye validación del array dinámico de asignaciones.
 */
class StoreDocenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo el administrador puede crear docentes
        return $this->user()->esAdmin();
    }

    public function rules(): array
    {
        return [
            'nombre'                    => ['required', 'string', 'max:100'],
            'apellido'                  => ['required', 'string', 'max:100'],
            'email'                     => ['required', 'email', 'unique:users,email'],
            'password'                  => ['required', 'string', 'min:8'],

            // Validación del array dinámico de asignaciones (puede venir vacío)
            'asignaciones'              => ['nullable', 'array'],
            'asignaciones.*.curso_id'   => ['required_with:asignaciones.*', 'exists:cursos,id'],
            'asignaciones.*.grado_id'   => ['required_with:asignaciones.*', 'exists:grados,id'],
            'asignaciones.*.seccion_id' => ['required_with:asignaciones.*', 'exists:secciones,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'                    => 'El nombre es obligatorio.',
            'apellido.required'                  => 'El apellido es obligatorio.',
            'email.required'                     => 'El correo electrónico es obligatorio.',
            'email.unique'                       => 'Este correo ya está registrado.',
            'password.required'                  => 'La contraseña es obligatoria.',
            'password.min'                       => 'La contraseña debe tener al menos 8 caracteres.',
            'asignaciones.*.curso_id.required_with' => 'Selecciona un curso para cada asignación.',
            'asignaciones.*.grado_id.required_with' => 'Selecciona un grado para cada asignación.',
            'asignaciones.*.seccion_id.required_with' => 'Selecciona una sección para cada asignación.',
        ];
    }
}
