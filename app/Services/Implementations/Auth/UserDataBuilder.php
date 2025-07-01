<?php

namespace App\Services\Implementations\Auth;

class UserDataBuilder
{
    private array $userData = [];

    public function withBasicInfo($user): self
    {
        $this->userData = [
            'id' => $user->id,
            'email' => $user->email
        ];
        return $this;
    }

    public function withRoles($roles): self
    {
        $this->userData['roles'] = $roles->map(fn($role) => [
            'rol_id' => $role->id,
            'nombre' => $role->nombre
        ])->all();
        return $this;
    }

    public function withBusinessLogic($usuario): self
    {
        $usuario->load(['tenant', 'restaurante', 'asignacionPersonal.sucursal']);

        $this->userData['tenant'] = $usuario->tenant ? [
            'tenant_id' => $usuario->tenant->id,
            'dominio' => $usuario->tenant->dominio
        ] : null;

        $this->userData['restaurante'] = $usuario->restaurante ? [
            'restaurante_id' => $usuario->restaurante->id,
            'nombre' => $usuario->restaurante->nombre_legal
        ] : null;

        $this->userData['asignacion_personal'] = $usuario->asignacionPersonal ? [
            'id' => $usuario->asignacionPersonal->id,
            'sucursal' => $usuario->asignacionPersonal->sucursal ? [
                'sucursal_id' => $usuario->asignacionPersonal->sucursal->id,
                'nombre' => $usuario->asignacionPersonal->sucursal->nombre
            ] : null
        ] : null;

        $this->userData['sucursal_id'] = $usuario->asignacionPersonal?->sucursal?->id;

        return $this;
    }

    public function build(): array
    {
        return $this->userData;
    }
}
