<?php

namespace App\Services\Interfaces;

interface IJwtManager
{
    public function attempt(array $credentials): bool;

    public function createTokenResponse(): array;

    public function createTokenResponseForUser($user): array;

    public function invalidateCurrentToken(): void;

    public function getAuthenticatedUser();

    public function refreshAccessToken(string $refreshToken): string;

    public function createRefreshToken($user): string;

    public function respondWithToken(string $token, array $additionalData = []): array;
}
