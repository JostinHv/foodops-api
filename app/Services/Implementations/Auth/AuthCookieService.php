<?php

namespace App\Services\Implementations\Auth;

use Symfony\Component\HttpFoundation\Cookie;

class AuthCookieService
{
    public function createAuthCookies(string $accessToken, string $refreshToken): array
    {
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
        return cookie(
            $name,
            $value,
            $minutes,
            '/',
            null,
            true,
            true,
            false,
            'none'
        );
    }
}
