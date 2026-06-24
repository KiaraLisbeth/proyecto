<?php

namespace App\Http\Requests\Docente;

use App\Models\Archivo;
use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest para validar la subida de archivos por parte del docente.
 * Valida tipo, tamaño (50MB máx.) y que la asignación pertenezca al docente.
 */
class StoreArchivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esDocente();
    }

    public function rules(): array
    {
        return [
            // El archivo es obligatorio y debe ser uno de los tipos permitidos
            'archivo' => [
                'required',
                'file',
                'max:51200', // 50 MB en kilobytes (50 * 1024)
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
                // Verificar que el docente no haya subido ya un archivo con el mismo nombre
                function ($attribute, $value, $fail) {
                    if ($value && $value->isValid()) {
                        $nombreOriginal = $value->getClientOriginalName();
                        $yaExiste = Archivo::withTrashed()
                            ->where('user_id', $this->user()->id)
                            ->whereRaw('LOWER(nombre_original) = LOWER(?)', [$nombreOriginal])
                            ->exists();

                        if ($yaExiste) {
                            $fail("Ya existe un archivo con el nombre \"{$nombreOriginal}\".");
                        }
                    }
                },
            ],

            // El bimestre es obligatorio (1=I, 2=II, 3=III, 4=IV)
            'bimestre' => ['required', 'integer', 'between:1,4'],

            // La descripción es opcional
            'descripcion' => ['nullable', 'string', 'max:500'],

            // La asignación debe pertenecer al docente autenticado
            'asignacion_id' => [
                'required',
                'integer',
                // Verificar que la asignación existe y pertenece al docente
                function ($attribute, $value, $fail) {
                    $existe = $this->user()
                                   ->asignaciones()
                                   ->where('id', $value)
                                   ->exists();

                    if (!$existe) {
                        $fail('La asignación seleccionada no es válida o no te pertenece.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'archivo.required'       => 'Debes seleccionar un archivo.',
            'archivo.max'            => 'El archivo no puede superar los 50 MB.',
            'archivo.mimes'          => 'Solo se permiten archivos PDF, Word, Excel o PowerPoint.',
            'bimestre.required'      => 'Debes seleccionar el bimestre.',
            'bimestre.between'       => 'El bimestre debe ser I, II, III o IV.',
            'asignacion_id.required' => 'Debes seleccionar una asignación.',
        ];
    }
}
