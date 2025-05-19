<?php

namespace App\Services\Implementations\Auth;

use App\Models\Usuario;
use App\Services\Interfaces\IJwtManager;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class JwtManager implements IJwtManager
{
    public function attempt(array $credentials): bool
    {
        return (bool)JWTAuth::attempt($credentials);
    }

    public function createTokenResponse(): array
    {
        $token = JWTAuth::getToken();
        return $this->buildTokenResponse($token);
    }

    public function createTokenResponseForUser($user): array
    {
        $token = JWTAuth::fromUser($user);
        return $this->buildTokenResponse($token, $user);
    }

    public function invalidateCurrentToken(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function getAuthenticatedUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    /**
     * @throws JWTException
     */
    public function refreshAccessToken(string $refreshToken): string
    {
        try {
            $payload = JWTAuth::manager()->getJWTProvider()->decode($refreshToken);
            if ($payload['exp'] <= now()->timestamp) {
                throw new JWTException('El token de refresco ha expirado');
            }

            // Obtener el usuario del payload del refresh token
            $userId = $payload['sub'];
            $user = Usuario::find($userId);

            if (!$user) {
                throw new JWTException('Usuario no encontrado');
            }

            // Generar nuevo access token
            return JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            throw new JWTException('Error al refrescar token: ' . $e->getMessage());
        }
    }

    public function createRefreshToken($user): string
    {
        // Crear claims personalizados para el refresh token
        $customClaims = [
            'sub' => $user->id,
            'iat' => now()->timestamp,
            'nbf' => now()->timestamp,
            'exp' => now()->addMinutes(config('jwt.refresh_ttl'))->timestamp,
            'jti' => uniqid('refresh_', true),
            'prv' => 'refresh_token'  // Identificador específico para refresh tokens
        ];
        // Crear payload con los claims personalizados
        $payload = JWTFactory::customClaims($customClaims)->make();

        return JWTAuth::encode($payload)->get();
    }

    private function buildTokenResponse(string $token, $user = null): array
    {
        $user = $user ?? JWTAuth::user();
        $expiration = config('jwt.ttl') * 60;

        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration,
            'user' => $user,
        ];
    }

    public function respondWithToken(string $token, array $additionalData = []): array
    {
        // Configuración centralizada y valores predeterminados
        $expiresIn = config('jwt.ttl') * 60;
        $tokenType = config('jwt.token_type', 'bearer');

        // Respuesta del token base
        $response = [
            'access_token' => $token,
            'token_type' => $tokenType,
            'expires_in' => $expiresIn,
        ];
        // Combinar datos adicionales si los hay (por ejemplo, datos del usuario o roles)
        return array_merge($response, $additionalData);
    }


}
