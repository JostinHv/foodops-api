<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiCheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userRoles = $user->roles->pluck('nombre')->toArray();

            if (empty(array_intersect($roles, $userRoles))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No tiene permiso para acceder a esta ruta',
                    'code' => 403
                ], 403);
            }

            return $next($request);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de autenticación',
                'code' => 401
            ], 401);
        }
    }
}
