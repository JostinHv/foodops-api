<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiAuthenticate extends Middleware
{
    private const UNAUTHORIZED = Response::HTTP_UNAUTHORIZED;

    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards): void
    {
        abort($this->buildErrorResponse('No autenticado'));
    }

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $jwtCookie = $request->cookie('access_token');

            $this->setTokenFromCookie($request);

            $this->validateBearerToken($request);

            $this->authenticate($request, $guards);

            return $next($request);
        } catch (Exception $e) {
            return $this->buildErrorResponse('Token inválido');
        }
    }

    /**
     * @throws Exception
     */
    private function setTokenFromCookie(Request $request): void
    {
        if ($jwtCookie = $request->cookie('access_token')) {
            try {

                $token = JWTAuth::setToken($jwtCookie);

                // Verificamos si el token se seteó correctamente
                $tokenString = $token->getToken();

                // Si llegamos aquí, el token es válido
                $request->headers->set('Authorization', "Bearer {$tokenString}");

            } catch (Exception $e) {
                throw $e;
            }
        } else {
            Log::warning('No se encontró cookie access_token');
        }
    }

    private function validateBearerToken(Request $request): void
    {
        if (!$request->bearerToken()) {
            abort($this->buildErrorResponse('Token no proporcionado'));
        }
    }

    private function buildErrorResponse(string $message): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'code' => self::UNAUTHORIZED
        ], self::UNAUTHORIZED);
    }

}
