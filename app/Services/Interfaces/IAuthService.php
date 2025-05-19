<?php

namespace App\Services\Interfaces;

interface IAuthService
{
    public function login(array $credentials): array;

    public function logout(): array;

    public function register(array $data): array;

    public function me(): array;

    public function refresh(array $data): array;

    public function comprobarEmail(mixed $email);

    public function autenticarse(array $credentials);
}
