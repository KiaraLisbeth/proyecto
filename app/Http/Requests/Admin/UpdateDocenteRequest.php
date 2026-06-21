<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esAdmin();
    }

    public function rules(): array
    {
        $docenteId = $this->route('docente');

        return [
            'username'                  => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z0-9._-]+$/', Rule::unique('users', 'username')->ignore($docenteId)],
            'password'                  => ['nullable', 'string', 'min:8', 'max:8'],

            'asignaciones'              => ['nullable', 'array'],
            'asignaciones.*.curso_nombre' => ['required_with:asignaciones.*', 'string', 'max:100'],
            'asignaciones.*.grado_id'   => ['required_with:asignaciones.*', 'exists:grados,id'],
            'asignaciones.*.seccion_id' => ['required_with:asignaciones.*', 'exists:secciones,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required'  => 'El nombre de usuario es obligatorio.',
            'username.unique'    => 'Este nombre de usuario ya está registrado.',
            'username.regex'     => 'Solo letras, números, puntos, guiones y guiones bajos.',
            'password.min'       => 'La contraseña debe tener exactamente 8 caracteres.',
            'password.max'       => 'La contraseña debe tener exactamente 8 caracteres.',
            'asignaciones.*.curso_nombre.required_with' => 'Escribe o selecciona un curso.',
            'asignaciones.*.grado_id.required_with'   => 'Selecciona un grado.',
            'asignaciones.*.seccion_id.required_with' => 'Selecciona una sección.',
        ];
    }
}
