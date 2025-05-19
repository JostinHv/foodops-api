<?php

namespace App\Services\Implementations\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginRateLimiter
{
    private const MAX_ATTEMPTS_PER_IP = 100;
    private const MAX_ATTEMPTS_PER_EMAIL = 10;
    private const MAX_ATTEMPTS_PER_EMAIL_IP = 5;
    private const DECAY_MINUTES = 5;

    public function tooManyAttempts(string $email): array
    {
        $ipKey = $this->getIpKey();
        $emailKey = $this->getEmailKey($email);
        $combinedKey = $this->getCombinedKey($email);

        // Verificar límites por IP
        if (RateLimiter::tooManyAttempts($ipKey, self::MAX_ATTEMPTS_PER_IP)) {
            return $this->buildBlockedResponse($ipKey, 'IP bloqueada por demasiados intentos.');
        }

        // Verificar límites por email
        if (RateLimiter::tooManyAttempts($emailKey, self::MAX_ATTEMPTS_PER_EMAIL)) {
            return $this->buildBlockedResponse($emailKey, 'Email bloqueado temporalmente por seguridad.');
        }

        // Verificar límites por combinación email + IP
        if (RateLimiter::tooManyAttempts($combinedKey, self::MAX_ATTEMPTS_PER_EMAIL_IP)) {
            return $this->buildBlockedResponse($combinedKey, 'Demasiados intentos desde esta ubicación.');
        }

        return ['blocked' => false];
    }

    public function incrementAttempts(string $email): array
    {
        $keys = [
            $this->getIpKey(),
            $this->getEmailKey($email),
            $this->getCombinedKey($email)
        ];

        foreach ($keys as $key) {
            RateLimiter::hit($key, self::DECAY_MINUTES * 60);
        }

        // Obtener intentos restantes del límite más restrictivo
        $remainingAttempts = min(
            self::MAX_ATTEMPTS_PER_EMAIL_IP - RateLimiter::attempts($this->getCombinedKey($email)),
            self::MAX_ATTEMPTS_PER_EMAIL - RateLimiter::attempts($this->getEmailKey($email))
        );

        return [
            'remaining_attempts' => max($remainingAttempts, 0),
            'message' => "Intento fallido. Le quedan {$remainingAttempts} intentos",
            'details' => $this->getAttemptDetails($email)
        ];
    }

    public function clearAttempts(string $email): void
    {
        RateLimiter::clear($this->getIpKey());
        RateLimiter::clear($this->getEmailKey($email));
        RateLimiter::clear($this->getCombinedKey($email));
    }

    private function buildBlockedResponse(string $key, string $message): array
    {
        $seconds = RateLimiter::availableIn($key);
        return [
            'blocked' => true,
            'minutes' => ceil($seconds / 60),
            'seconds' => $seconds,
            'next_attempt' => Carbon::now()->addSeconds($seconds)->format('Y-m-d H:i:s'),
            'message' => $message . " Por favor espere {$this->formatTimeRemaining($seconds)}",
        ];
    }

    private function getAttemptDetails(string $email): array
    {
        return [
            'ip_attempts' => RateLimiter::attempts($this->getIpKey()),
            'email_attempts' => RateLimiter::attempts($this->getEmailKey($email)),
            'combined_attempts' => RateLimiter::attempts($this->getCombinedKey($email)),
            'ip_remaining' => self::MAX_ATTEMPTS_PER_IP - RateLimiter::attempts($this->getIpKey()),
            'email_remaining' => self::MAX_ATTEMPTS_PER_EMAIL - RateLimiter::attempts($this->getEmailKey($email)),
            'combined_remaining' => self::MAX_ATTEMPTS_PER_EMAIL_IP - RateLimiter::attempts($this->getCombinedKey($email))
        ];
    }

    private function getIpKey(): string
    {
        return 'login_ip:' . request()->ip();
    }

    private function getEmailKey(string $email): string
    {
        return 'login_email:' . Str::lower($email);
    }

    private function getCombinedKey(string $email): string
    {
        return 'login_combined:' . Str::lower($email) . '|' . request()->ip();
    }

    private function formatTimeRemaining(int $seconds): string
    {
        if ($seconds < 60) {
            return "{$seconds} segundos";
        }

        $minutes = ceil($seconds / 60);
        return "{$minutes} " . ($minutes === 1 ? 'minuto' : 'minutos');
    }
}

