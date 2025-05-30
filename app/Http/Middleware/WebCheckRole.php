<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class WebCheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userRoles = $user->roles->pluck('nombre')->toArray();

            if (empty(array_intersect($roles, $userRoles))) {
                \Log::log('warning', 'Acceso denegado para el usuario: {user}', ['user' => $user->id]);
                return redirect()
                    ->route('login')
                    ->with('error', 'No tiene permiso para acceder a esta página');
            }

            return $next($request);

        } catch (\Exception $e) {
            return redirect()
                ->route('login')
                ->with('error', 'Por favor inicie sesión para continuar');
        }
    }
}
