<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Usuario;
use App\Services\Implementations\Auth\AuthCookieService;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IUsuarioService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private IAuthService $authService;
    private IUsuarioService $usuarioService;
    private AuthCookieService $authCookieService;

    public function __construct(IAuthService      $authService,
                                IUsuarioService   $usuarioService,
                                AuthCookieService $authCookieService)
    {
        $this->authService = $authService;
        $this->usuarioService = $usuarioService;
        $this->authCookieService = $authCookieService;
    }

    public function showRegisterForm(): View|Application|Factory
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $emailExists = $this->authService->comprobarEmail($validatedData['email']);

        if ($emailExists === true) {
            return back()->withErrors(['email' => 'El email ya está registrado.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $response = $this->authService->register($validatedData);

        if ($response['error'] === true) {
            return back()->withErrors(['error' => $response['message']])
                ->withInput($request->except('password', 'password_confirmation'));
        }
        $cookies = $this->authCookieService->createAuthCookies(
            $response['data']['access_token'],
            $response['data']['refresh_token']
        );

        return $this->redirectToDashboard($response['data']['user'])
            ->with(['success' => $response['message']])
            ->withCookies($cookies);
    }

    public function showLoginForm(): View|Application|Factory
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $response = $this->authService->login($validatedData);

        if ($response['error'] === true) {
            return back()->withErrors(['credentials' => $response['message']])
                ->withInput($request->except('password'));
        }
        // Asegurarnos de que el token sea el JWT completo
        $accessToken = $response['data']['access_token'];

        // Verificar que el token tenga 3 segmentos
        if (count(explode('.', $accessToken)) !== 3) {
            Log::error('Token inválido generado:', ['token_segments' => count(explode('.', $accessToken))]);
            return back()->withErrors(['error' => 'Error de autenticación']);
        }

        $cookies = $this->authCookieService->createAuthCookies(
            $response['data']['access_token'],
            $response['data']['refresh_token']
        );
        $this->usuarioService->actualizar($response['data']['user']['id'], [
            'ultimo_acceso' => now()
        ]);
        return $this->redirectToDashboard($response['data']['user'])
            ->with(['success' => $response['message']])
            ->withCookies($cookies);
    }

    public function logout(Request $request): RedirectResponse
    {
        // Obtener el token de la cookie
        $token = $request->cookie('access_token');

        if ($token) {
            // Intentar hacer logout en el servicio de autenticación
            $this->authService->logout($token);
        }

        // Eliminar las cookies de autenticación
        $cookies = $this->authCookieService->removeAuthCookies();

        return redirect()->route('login')
            ->with(['success' => 'Sesión cerrada correctamente'])
            ->withCookies($cookies);
    }

    public function checkEmail(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $exists = $this->authService->comprobarEmail($email);

        return response()->json(['exists' => $exists]);
    }

    /**
     * Redirige al usuario a su dashboard correspondiente según su rol
     */
    private function redirectToDashboard($userData): RedirectResponse
    {
        // Convertir el array de usuario a un objeto User
        $user = Usuario::with('roles')->find($userData['id']);
        if (!$user) {
            return redirect()->route('home');
        }

        // Verificar si el usuario tiene el rol superadmin
        if ($user->roles->contains('nombre', 'superadmin')) {
            return redirect()->route('superadmin.tenant');
        }

        // Verificar si el usuario tiene el rol admin
        if ($user->roles->contains('nombre', 'administrador')) {
            return redirect()->route('tenant.grupo-restaurant');
        }

        // Verificar si el usuario tiene el rol gerente
        if ($user->roles->contains('nombre', 'gerente')) {
            return redirect()->route('gerente.menu');
        }

        if ($user->roles->contains('nombre', 'cajero')) {
            return redirect()->route('cajero.facturacion');
        }

        if ($user->roles->contains('nombre', 'cocinero')) {
            return redirect()->route('cocinero.orden.index');
        }

        // Verificar si el usuario tiene el rol mesero
        if ($user->roles->contains('nombre', 'mesero')) {
            return redirect()->route('mesero.orden.index');
        }

        // Si no tiene ningún rol específico, redirigir al home
        return redirect()->route('home');
    }
}
