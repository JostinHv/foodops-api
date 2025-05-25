<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Implementations\Auth\AuthCookieService;
use App\Services\Interfaces\IAuthService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private IAuthService $authService;
    private AuthCookieService $authCookieService;

    public function __construct(IAuthService $authService, AuthCookieService $authCookieService)
    {
        $this->authService = $authService;
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

        return redirect()->route('home')
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

        $cookies = $this->authCookieService->createAuthCookies(
            $response['data']['access_token'],
            $response['data']['refresh_token']
        );
        return redirect()->route('home')
            ->with(['credentials' => $response['message']])
            ->withCookies($cookies);
    }


    public function checkEmail(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $exists = $this->authService->comprobarEmail($email);

        return response()->json(['exists' => $exists]);
    }


}
