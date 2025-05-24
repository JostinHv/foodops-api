<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\AuthResponse;
use App\Services\Implementations\Auth\AuthCookieService;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutenticacionController extends Controller
{
    private IAuthService $authService;
    private AuthCookieService $authCookieService;

    public function __construct(IAuthService $authService, AuthCookieService $authCookieService)
    {
        $this->middleware('auth:api', ['except' =>
            [
                'login',
                'register',
//                'refresh',
//                'comprobarEmail',
                'autenticarse',
                'refresh',
            ]
        ]);
        $this->authService = $authService;
        $this->authCookieService = $authCookieService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        if ($result['error']) {
            return AuthResponse::error(
                $result['message'],
                $result['details'] ?? null,
            );
        }

        $cookies = $this->authCookieService->createAuthCookies(
            $result['data']['access_token'],
            $result['data']['refresh_token']
        );

        return AuthResponse::withCookies(
            $result['message'],
            ['user' => $result['data']['user']],
            $cookies
        );
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials);

        if ($result['error']) {
            return AuthResponse::error(
                $result['message'],
                $result['details'] ?? null,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $cookies = $this->authCookieService->createAuthCookies(
            $result['data']['access_token'],
            $result['data']['refresh_token']
        );

        return AuthResponse::withCookies(
            $result['message'],
            ['user' => $result['data']['user']],
            $cookies
        );
    }

    public function logout(): JsonResponse
    {
        $result = $this->authService->logout();
        if ($result['error']) {
            return AuthResponse::error(
                $result['message'],
                ['error' => $result['error']]
            );
        }
        return AuthResponse::success($result['message'], $result['data']);
    }

    public function me(): JsonResponse
    {
        $result = $this->authService->me();
        if ($result['error']) {
            return AuthResponse::error(
                $result['message'],
                ['error' => $result['error']]
            );
        }
        return AuthResponse::success($result['message'], $result['data']);
    }

    public function refresh(Request $request): JsonResponse
    {
        if (!$refreshToken = $request->cookie('refresh_token')) {
            return AuthResponse::error(
                'El refresh token es requerido',
                ['error' => 'Refresh token no proporcionado'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $result = $this->authService->refresh(['refresh_token' => $refreshToken]);

        if ($result['error']) {
            return AuthResponse::error(
                'Error al refrescar el token',
                ['error' => $result['error']],
                Response::HTTP_UNAUTHORIZED
            );
        }


        $cookies = $this->authCookieService->createAuthCookies(
            $result['access_token'],
            $refreshToken
        );

        return AuthResponse::withCookies(
            'Token actualizado exitosamente',
            ['token' => $result],
            $cookies
        );
    }

    public function comprobarEmail(string $email): JsonResponse
    {
        $resultBool = $this->authService->comprobarEmail($email);
        return response()->json([
            'message' => 'Email comprobado exitosamente.',
            'data' => $resultBool,
            'sub-message' => $resultBool ? 'El email ya esta registrado.' : 'El email no esta registrado.'
        ], Response::HTTP_OK);
    }

    public function autenticarse(): JsonResponse
    {
        $credentials = request(['email', 'password']);
        $result = $this->authService->autenticarse($credentials);
        if (isset($result['error'])) {
            return response()->json([
                'message' => 'Error al autenticar el usuario.',
                'error' => $result['error']
            ], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json([
            'message' => 'Usuario autenticado exitosamente.',
            'data' => $result
        ], Response::HTTP_OK);
    }

}
