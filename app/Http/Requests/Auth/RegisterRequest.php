<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ValidacionRequest;

class RegisterRequest extends ValidacionRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'password' => 'required|string|confirmed',
            //Para Cliente
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'nro_celular' => 'nullable|string|max:20',
//            'tipo_documento_id' => 'nullable|integer',
//            'nombres' => 'required|string|max:255',
//            'apellidos' => 'required|string|max:255',
//            'nro_celular' => 'nullable|string|max:20',
//            'nro_documento' => 'nullable|string|max:20',
//            'genero' => 'nullable|string|max:20',
//            'fecha_cumpleanios' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El email es requerido',
            'email.string' => 'El email debe ser un texto',
            'email.email' => 'El email debe ser un correo electrónico',
            'email.max' => 'El email debe tener como máximo 255 caracteres',
            'email.unique' => 'El email ya está en uso',
            'password.required' => 'La contraseña es requerida',
            //Para Cliente
//            'tipo_documento_id.integer' => 'El tipo de documento debe ser un número entero',
//            'nombres.required' => 'El nombre es requerido',
//            'nombres.string' => 'El nombre debe ser un texto',
//            'nombres.max' => 'El nombre debe tener como máximo 255 caracteres',
//            'apellidos.required' => 'El apellido es requerido',
//            'apellidos.string' => 'El apellido debe ser un texto',
//            'apellidos.max' => 'El apellido debe tener como máximo 255 caracteres',
//            'nro_celular.string' => 'El número de celular debe ser un texto',
//            'nro_celular.max' => 'El número de celular debe tener como máximo 20 caracteres',
//            'nro_documento.string' => 'El número de documento debe ser un texto',
//            'nro_documento.max' => 'El número de documento debe tener como máximo 20 caracteres',
//            'genero.string' => 'El género debe ser un texto',
//            'genero.max' => 'El género debe tener como máximo 20 caracteres',
//            'fecha_cumpleanios.date' => 'La fecha de cumpleaños debe ser una fecha',
        ];
    }
}
