<?php

namespace App\Traits;

use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

trait AuthenticatedUserTrait
{
    protected function getCurrentUser(): ?Usuario
    {
        return auth()->user();
    }

    protected function logUserInfo(): void
    {
        $usuario = $this->getCurrentUser();

        if (!$usuario) {
            Log::warning('Intento de acceso sin autenticación');
            return;
        }

        Log::info('Datos del usuario autenticado', [
            'id' => $usuario->id ?? 'N/A',
            'email' => $usuario->email ?? 'N/A',
            'nombres' => $usuario->nombres ?? 'N/A',
            'apellidos' => $usuario->apellidos ?? 'N/A',
            'roles' => $usuario->roles?->pluck('nombre')->all() ?? []
        ]);
    }

    protected function validateAuthenticatedUser(): bool
    {
        if (!$this->getCurrentUser()) {
            Log::error('Usuario no autenticado intentando acceder a recurso protegido');
            return false;
        }
        return true;
    }
}
