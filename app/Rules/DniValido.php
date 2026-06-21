<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DniValido implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Debe tener exactamente 8 números
        if (!preg_match('/^\d{8}$/', $value)) {
            $fail('El DNI debe tener exactamente 8 dígitos.');
            return;
        }

        // 2. No permitir todos los dígitos iguales (ej: 00000000, 11111111, etc.)
        if (preg_match('/^(\d)\1{7}$/', $value)) {
            $fail('El DNI ingresado no es válido (todos los dígitos son idénticos).');
            return;
        }

        // 3. No permitir secuencias numéricas ascendentes o descendentes continuas
        $secuenciasInvalidas = [
            '01234567', '12345678', '23456789',
            '76543210', '87654321', '98765432'
        ];
        if (in_array($value, $secuenciasInvalidas)) {
            $fail('El DNI ingresado no es válido (es una secuencia numérica).');
            return;
        }
    }
}
