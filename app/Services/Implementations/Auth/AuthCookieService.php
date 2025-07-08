<?php

namespace App\Services\Implementations\Auth;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Cookie;

class AuthCookieService
{
    public function createAuthCookies(string $accessToken, string $refreshToken): array
    {
        // Validar formato del token
        if (count(explode('.', $accessToken)) !== 3) {
            Log::error('Intento de crear cookie con token inválido', [
                'token_segments' => count(explode('.', $accessToken))
            ]);
            throw new \RuntimeException('Token JWT inválido');
        }

        return [
            'access' => $this->createCookie(
                'access_token',
                $accessToken,
                config('jwt.ttl')
            ),
            'refresh' => $this->createCookie(
                'refresh_token',
                $refreshToken,
                config('jwt.refresh_ttl')
            )
        ];
    }

    private function createCookie(string $name, string $value, int $minutes): Cookie
    {
        Log::info('Cookie: ' . $value);
        return cookie(
            $name,
            $value,
            $minutes,
            '/',
            null,
            false,
            true,
            false,
            'lax'
        );
    }

    public function removeAuthCookies(): array
    {
        return [
            'access' => $this->createCookie('access_token', '', -1),
            'refresh' => $this->createCookie('refresh_token', '', -1)
        ];
    }
}
