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

    public function withClientData($cliente): self
    {
        $this->userData['cliente'] = $cliente->only([
            'id', 'nombres', 'apellidos', 'genero'
        ]);
        return $this;
    }

    public function build(): array
    {
        return $this->userData;
    }
}
