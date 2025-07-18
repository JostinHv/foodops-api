<?php

namespace App\Repositories\Interfaces;

use App\Services\Interfaces\IBaseService;
use Illuminate\Database\Eloquent\Collection;

interface IActivoBoolRepository
{
    /**
     * Cambia el estado de un registro automáticamente.
     *
     * @param int $id
     * @return bool
     */
    public function cambiarEstadoAutomatico(int $id): bool;

    /**
     * Cambia el estado de un registro.
     *
     * @param int $id
     * @param int $activo
     * @return bool
     */
    public function cambiarEstado(int $id, int $activo): bool;

    /**
     * Obtiene los registros activos.
     *
     * @return Collection
     */
    public function obtenerActivos(): Collection;

    /**
     * Obtiene el ultimo registro activo.
     *
     * @return Collection
     */
    public function obtenerUltimoActivo(): Collection;
}
