<?php

namespace App\Services\Interfaces;

interface IAuthCookieService
{
    public function createAuthCookies(string $accessToken, string $refreshToken): array;
    public function removeAuthCookies(): array;
}
