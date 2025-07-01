<?php

namespace App\Repositories\Implementations;

use App\Models\Rol;
use App\Models\Usuario;
use App\Repositories\Interfaces\IUsuarioRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsuarioRepository extends ActivoBoolRepository implements IUsuarioRepository
{
    public function __construct(Usuario $modelo)
    {
        parent::__construct($modelo);
    }

    public function registrarUsuarioConRol(array $datos): Model
    {
        return DB::transaction(function () use ($datos) {
            try {
                // Verificar si el rol "USUARIO" existe, si no, crearlo
                $rol = Rol::firstOrCreate(
                    ['nombre' => 'cliente'],
                    ['descripcion' => 'Rol de cliente', 'activo' => true]
                );

                // Crear el usuario
                $usuario = $this->modelo->create($datos);

                // Asignar el rol al usuario
                $usuario->roles()->attach($rol->id);

                return $usuario;
            } catch (\Exception $e) {
                Log::error('Error al registrar usuario con rol: ' . $e->getMessage());
                throw $e;
            }
        }, 5);  // 5 intentos de reintentar en caso de deadlock
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {

    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm) {
            if ($searchColumn) {
                $consulta->where($searchColumn, 'like', '%' . $searchTerm . '%');
            }
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField && $sortOrder) {
            $consulta->orderBy($sortField, $sortOrder);
        }
    }

    public function existeEmailRegistrado(string $email): bool
    {
        return $this->modelo->where('email', $email)->exists();
    }

    public function obtenerPorTenantId(int $tenantId): Collection
    {
        return $this->modelo->where('tenant_id', $tenantId)
            ->with(['roles', 'fotoPerfil', 'restaurante'])
            ->get()
            ->filter(function ($usuario) {
                return !$usuario->roles->whereIn('id', [1, 2])->count();
            });
    }

    public function obtenerTodosPorTenantId(int $tenantId): Collection
    {
        return $this->modelo->where('tenant_id', $tenantId)
            ->with(['roles', 'fotoPerfil', 'restaurante'])
            ->get();
    }

    public function obtenerPorEmail(string $email)
    {
        return $this->modelo->where('email', $email)
            ->with(['tenant'])
            ->first();
    }
}
