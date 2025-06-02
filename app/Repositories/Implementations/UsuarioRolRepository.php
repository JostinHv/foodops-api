<?php

namespace App\Repositories\Implementations;

use App\Models\UsuarioRol;
use App\Repositories\Interfaces\IUsuarioRolRepository;
use Illuminate\Database\Eloquent\Builder;

class UsuarioRolRepository extends ActivoBoolRepository implements IUsuarioRolRepository
{

    public function __construct(UsuarioRol $modelo
    )
    {
        parent::__construct($modelo);
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

    /**
     * @throws \Throwable
     */
    public function actualizarRolUsuario(int $usuarioId, mixed $rol_id): bool
    {
        if ($this->modelo->where('usuario_id', $usuarioId)->doesntExist()) {
            $this->crear([
                'usuario_id' => $usuarioId,
                'rol_id' => $rol_id,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return true; // No existe el usuario con ese ID
        }
        return $this->modelo
            ->where('usuario_id', $usuarioId)
            ->update(['rol_id' => $rol_id]);

    }
}
