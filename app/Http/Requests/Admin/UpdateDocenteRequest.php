<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * FormRequest para validar la actualización de datos de un docente.
 * El email debe ser único excepto para el docente que se está editando.
 */
class UpdateDocenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esAdmin();
    }

    public function rules(): array
    {
        // Obtener el ID del docente desde la ruta para la regla unique
        $docenteId = $this->route('docente');

        return [
            'nombre'                    => ['required', 'string', 'max:100'],
            'apellido'                  => ['required', 'string', 'max:100'],
            'email'                     => ['required', 'email', Rule::unique('users', 'email')->ignore($docenteId)],
            'password'                  => ['nullable', 'string', 'min:8'], // Opcional en edición

            // Validación del array dinámico de asignaciones
            'asignaciones'              => ['nullable', 'array'],
            'asignaciones.*.curso_id'   => ['required_with:asignaciones.*', 'exists:cursos,id'],
            'asignaciones.*.grado_id'   => ['required_with:asignaciones.*', 'exists:grados,id'],
            'asignaciones.*.seccion_id' => ['required_with:asignaciones.*', 'exists:secciones,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellido.required'  => 'El apellido es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.unique'       => 'Este correo ya está registrado.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'asignaciones.*.curso_id.required_with' => 'Selecciona un curso.',
            'asignaciones.*.grado_id.required_with' => 'Selecciona un grado.',
            'asignaciones.*.seccion_id.required_with' => 'Selecciona una sección.',
        ];
    }
}
