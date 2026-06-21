<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\DniValido;

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
            'nombre'                    => ['required', 'string', 'max:100'],
            'apellido'                  => ['required', 'string', 'max:100'],
            'dni'                       => ['required', new DniValido, Rule::unique('users', 'dni')->ignore($docenteId)],
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
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellido.required'  => 'El apellido es obligatorio.',
            'dni.required'       => 'El DNI es obligatorio.',
            'dni.digits'         => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique'         => 'Este DNI ya está registrado por otro docente.',
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
