<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class WebAuthenticate extends Middleware
{

    protected function redirectTo(Request $request): string
    {
        return route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->setTokenFromCookie($request);
            $this->validateBearerToken($request);
            $this->authenticate($request, $guards);
            $response = $next($request);

            // Agregar headers para prevenir el caché
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');

            return $response;
        } catch (Exception $e) {
            return $this->buildErrorResponse();
        }
    }

    private function setTokenFromCookie(Request $request): void
    {
        if ($jwtCookie = $request->cookie('access_token')) {
            $token = JWTAuth::setToken($jwtCookie)->getToken();
            $request->headers->set('Authorization', "Bearer {$token}");
        }
    }

    private function validateBearerToken(Request $request): void
    {
        if (!$request->bearerToken()) {
            $this->buildErrorResponse();
        }
    }

    private function buildErrorResponse(): RedirectResponse
    {
        return redirect()->route('login')->with('error', 'Por favor inicie sesión para continuar');
    }
}
