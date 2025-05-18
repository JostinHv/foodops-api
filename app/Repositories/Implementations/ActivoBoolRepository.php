<?php

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\IActivoBoolRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class ActivoBoolRepository extends BaseRepository implements IActivoBoolRepository
{
    public function __construct(Model $modelo)
    {
        parent::__construct($modelo);
    }

    /**
     * Cambia el estado de un registro automáticamente.
     *
     * @param int $id
     * @return bool
     */
    public function cambiarEstadoAutomatico(int $id): bool
    {
        $modelo = $this->obtenerPorId($id);
        if ($modelo) {
            $modelo->activo = !$modelo->activo;
            return $modelo->save();
        }
        return false;
    }

    /**
     * Cambia el estado de un registro.
     *
     * @param int $id
     * @param int $activo
     * @return bool
     */
    public function cambiarEstado(int $id, int $activo): bool
    {
        $modelo = $this->obtenerPorId($id);
        if ($modelo) {
            $modelo->activo = $activo;
            return $modelo->save();
        }
        return false;
    }

    /**
     * Obtiene los registros activos.
     *
     * @return Collection
     */
    public function obtenerActivos(): Collection
    {
        return $this->modelo->where('activo', 1)->get();
    }

    /**
     * Obtiene el ultimo registro activo.
     *
     * @return Collection
     */
    public function obtenerUltimoActivo(): Collection
    {
        return $this->modelo->where('activo', 1)->latest()->first();
    }

}
