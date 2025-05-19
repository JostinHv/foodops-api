<?php

namespace App\Http\Requests;

class EstadoRequest extends ValidacionRequest
{

    public function rules(): array
    {
        return [
            'activo' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'activo.required' => 'El estado activo es obligatorio.',
            'activo.in' => 'El estado activo debe ser 0 o 1.',
        ];
    }
}
