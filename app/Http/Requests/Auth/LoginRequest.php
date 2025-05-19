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
            'email' => 'required|string|email|max:255|exists:usuarios,email',
            'password' => 'required|string',
        ];
    }
}
