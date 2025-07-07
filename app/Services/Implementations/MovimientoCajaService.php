<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IMovimientoCajaRepository;
use App\Services\Interfaces\IMovimientoCajaService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class MovimientoCajaService implements IMovimientoCajaService
{
    public function __construct(private IMovimientoCajaRepository $repository) {}

    public function obtenerTodos(): Collection
    {
        return $this->repository->obtenerTodos();
    }

    public function obtenerPorId(int $id): ?Model
    {
        return $this->repository->obtenerPorId($id);
    }

    public function crear(array $datos): Model
    {
        return $this->repository->crear($datos);
    }

    public function actualizar(int $id, array $datos): bool
    {
        return $this->repository->actualizar($id, $datos);
    }

    public function eliminar(int $id): bool
    {
        return $this->repository->eliminar($id);
    }

    public function obtenerPorCaja(int $cajaId): Collection
    {
        return $this->repository->obtenerPorCaja($cajaId);
    }

    public function registrarMovimiento(array $datos): Model
    {
        return $this->crear($datos);
    }

    public function obtenerMovimientosPorTipo(int $cajaId, int $tipoMovimientoId): Collection
    {
        return $this->repository->obtenerPorCaja($cajaId)
            ->where('tipo_movimiento_caja_id', $tipoMovimientoId);
    }

    public function calcularTotalPorTipo(int $cajaId, int $tipoMovimientoId): float
    {
        return $this->obtenerMovimientosPorTipo($cajaId, $tipoMovimientoId)
            ->sum('monto');
    }

    public function calcularTotalPorMetodoPago(int $cajaId, int $metodoPagoId): float
    {
        return $this->repository->obtenerPorCaja($cajaId)
            ->where('metodo_pago_id', $metodoPagoId)
            ->sum('monto');
    }
} 