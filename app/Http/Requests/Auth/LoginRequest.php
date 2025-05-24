<?php

namespace App\Http\Requests\Auth;


use App\Http\Requests\ValidacionRequest;

class LoginRequest extends ValidacionRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El campo correo es obligatorio.',
            'email.email' => 'El campo correo debe ser una dirección de correo electrónico válida.',
            'password.required' => 'El campo contraseña es obligatorio.',
        ];
    }


}
