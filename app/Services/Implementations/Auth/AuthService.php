<?php

namespace App\Services\Implementations\Auth;

use App\Repositories\Interfaces\IUsuarioRepository;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IJwtManager;
use App\Services\Interfaces\IUsuarioService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthService implements IAuthService
{
    private const MESSAGES = [
        'login' => [
            'success' => 'Inicio de sesión exitoso.',
            'error' => 'Credenciales inválidas. Por favor verifique su email y contraseña.',
            'blocked' => 'Cuenta temporalmente bloqueada por múltiples intentos fallidos.'
        ],
        'register' => [
            'success' => 'Usuario registrado exitosamente. ¡Bienvenido!',
            'error' => 'Error al registrar el usuario:'
        ],
        'logout' => [
            'success' => 'Sesión cerrada exitosamente. ¡Hasta pronto!',
            'error' => 'Error al cerrar sesión. Por favor intente nuevamente.'
        ],
        'bloqueado' => [
            'error' => 'Cuenta desactivada. Por favor, contacte al administrador.'
        ],
        'acceso' => [
            'error' => 'Usted no esta asignado a ninguna sucursal. Por favor, contacte al administrador.'
        ]
    ];

    private IUsuarioRepository $usuarioRepository;
    private IJwtManager $jwtManager;
    private LoginRateLimiter $rateLimiter;
    private IUsuarioService $usuarioService;

    public function __construct
    (
        IUsuarioRepository $usuarioRepository,
        IJwtManager        $jwtManager,
        LoginRateLimiter   $rateLimiter,
        IUsuarioService    $usuarioService
    )
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->jwtManager = $jwtManager;
        $this->rateLimiter = $rateLimiter;
        $this->usuarioService = $usuarioService;
    }


    public function login(array $credentials): array
    {
        $email = $credentials['email'] ?? null;
        $rateLimitCheck = $this->rateLimiter->tooManyAttempts($email);

        if ($rateLimitCheck['blocked']) {
            return $this->createErrorResponse(
                self::MESSAGES['login']['blocked'],
                $rateLimitCheck
            );

        }

        if (!$token = auth()->attempt($credentials)) {
            return $this->handleFailedLogin($email);
        }

        if (count(explode('.', $token)) !== 3) {
            Log::info('Intento de crear token con formato inválido');
            throw new \RuntimeException('Error al generar token JWT válido');
        }

        $usuario = $this->usuarioService->obtenerPorEmail($email);
        $bloqueado = $this->usuarioService->estaBloqueado($usuario->id);
        $tieneAcceso = $this->usuarioService->tieneAcceso($usuario);
        if ($bloqueado) {
            return $this->createErrorResponse(
                self::MESSAGES['bloqueado']['error']
            );
        }
        if (!$tieneAcceso) {
            return $this->createErrorResponse(
                self::MESSAGES['acceso']['error']
            );
        }
        return $this->handleSuccessfulLogin($token);
    }

    public function register(array $data): array
    {
        try {
            $user = $this->usuarioRepository->registrarUsuarioConRol($this->prepareUsuarioData($data));

            return $this->createTokenResponse($user, false);
        } catch (Exception $e) {
            return $this->createErrorResponse(self::MESSAGES['register']['error'] . $e->getMessage());
        }
    }

    public function logout(): array
    {
        try {
            $this->jwtManager->invalidateCurrentToken();
            return $this->createSuccessResponse(self::MESSAGES['logout']['success']);
        } catch (JWTException $e) {
            return $this->createErrorResponse(self::MESSAGES['logout']['error'], [$e->getMessage()]);
        }
    }

    public function me(): array
    {
        try {
            $user = $this->jwtManager->getAuthenticatedUser()->load(['roles', 'cliente']);
            $usuarioFormateado = $this->formatUser($user);
            return $this->createSuccessResponse('Datos del Usuario', $usuarioFormateado);
        } catch (TokenExpiredException $e) {
            return $this->createErrorResponse('El token ha expirado' . $e->getMessage());
        } catch (TokenInvalidException $e) {
            return $this->createErrorResponse('El token es invalido' . $e->getMessage());
        } catch (JWTException $e) {
            return $this->createErrorResponse('El token no fue proporcionado' . $e->getMessage());
        }
    }

    public function refresh(array $data): array
    {
        if (!isset($data['refresh_token'])) {
            return $this->createErrorResponse('Refresh token no proporcionado');
        }

        try {
            $newToken = $this->jwtManager->refreshAccessToken($data['refresh_token']);
            return $this->createTokenRefreshResponse($newToken);
        } catch (JWTException $e) {
            return $this->createErrorResponse('Error al refrescar token: ' . $e->getMessage());
        }
    }

    public function comprobarEmail(string $email): bool
    {
        return $this->usuarioRepository->existeEmailRegistrado($email);
    }

    public function autenticarse(array $credentials): array
    {
        $emailRegistrado = $this->comprobarEmail($credentials['email']);

        if ($emailRegistrado) {
            $resultadoLogin = $this->login($credentials);
            $resultadoLogin['code'] = 1;
            $resultadoLogin['type'] = 'login';
            return $resultadoLogin;
        }
        $resultadoRegistro = $this->register($credentials);
        $resultadoRegistro['code'] = 2;
        $resultadoRegistro['type'] = 'register';
        return $resultadoRegistro;
    }

    private function handleFailedLogin(string $email): array
    {
        $attemptInfo = $this->rateLimiter->incrementAttempts($email);
        return $this->createErrorResponse(
            self::MESSAGES['login']['error'],
            $attemptInfo
        );
    }

    private function handleSuccessfulLogin(string $token): array
    {
        $user = auth()->user();
        $this->rateLimiter->clearAttempts($user->email);

        return $this->createTokenResponse($user, $token);
    }

    private function createTokenResponse($user, bool $login = true, string $token = null): array
    {
        $accessToken = $token ?? $this->jwtManager->createTokenResponseForUser($user)['token'];
        $refreshToken = $this->jwtManager->createRefreshToken($user);

        return [
            'error' => false,
            'message' => $login ? self::MESSAGES['login']['success'] : self::MESSAGES['register']['success'],
            'data' => [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'user' => $this->formatUser($user)
            ]
        ];
    }

    private function createTokenRefreshResponse(string $token): array
    {
        return [
            'error' => false,
            'access_token' => $token,
            'expires_in' => config('jwt.ttl') * 60
        ];
    }

    private function createErrorResponse(string $message, array $details = null): array
    {
        return [
            'error' => true,
            'message' => $message,
            'details' => $details
        ];
    }

    private function createSuccessResponse(string $message, array $data = []): array
    {
        return [
            'error' => false,
            'message' => $message,
            'data' => $data
        ];
    }

    private function prepareUsuarioData(array $data): array
    {
        return [
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'nro_celular' => $data['nro_celular'] ?? null,
//            'cliente_id' => $clienteId
        ];
    }

    private function formatUser($user): array
    {
        return (new UserDataBuilder())
            ->withBasicInfo($user)
            ->withRoles($user->roles)
            ->withBusinessLogic($user)
            ->build();
    }
}
