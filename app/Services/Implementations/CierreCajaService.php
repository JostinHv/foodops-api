<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\ICierreCajaRepository;
use App\Services\Interfaces\ICierreCajaService;
use App\Services\Interfaces\IMovimientoCajaService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class CierreCajaService implements ICierreCajaService
{
    public function __construct(
        private ICierreCajaRepository $repository,
        private IMovimientoCajaService $movimientoCajaService
    ) {}

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

    public function registrarCierre(array $datos): Model
    {
        return $this->crear($datos);
    }

    public function calcularTotalesCierre(int $cajaId): array
    {
        $totalVentas = $this->movimientoCajaService->calcularTotalPorTipo($cajaId, 1); // VENTA
        $totalRetiros = $this->movimientoCajaService->calcularTotalPorTipo($cajaId, 2); // RETIRO
        $totalDepositos = $this->movimientoCajaService->calcularTotalPorTipo($cajaId, 3); // DEPOSITO
        $totalGastos = $this->movimientoCajaService->calcularTotalPorTipo($cajaId, 4); // GASTO

        $montoTarjetas = $this->movimientoCajaService->calcularTotalPorMetodoPago($cajaId, 2); // Tarjeta de Crédito
        $montoTarjetas += $this->movimientoCajaService->calcularTotalPorMetodoPago($cajaId, 3); // Tarjeta de Débito
        $montoTransferencias = $this->movimientoCajaService->calcularTotalPorMetodoPago($cajaId, 4); // Transferencia Bancaria
        $montoOtros = $this->movimientoCajaService->calcularTotalPorMetodoPago($cajaId, 5); // PayPal

        return [
            'total_ventas' => $totalVentas,
            'total_retiros' => $totalRetiros,
            'total_depositos' => $totalDepositos,
            'total_gastos' => $totalGastos,
            'monto_tarjetas' => $montoTarjetas,
            'monto_transferencias' => $montoTransferencias,
            'monto_otros' => $montoOtros,
        ];
    }
} 