<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IFacturaRepository;
use App\Services\Interfaces\IFacturaService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class FacturaService implements IFacturaService
{

    public function __construct(
        private IFacturaRepository $repository
    )
    {
    }

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

    public function obtenerPorSucursales(array $sucursalIds): Collection
    {
        return $this->repository->obtenerPorSucursales($sucursalIds);
    }

    public function obtenerPorOrden(int $ordenId): ?Model
    {
        return $this->repository->obtenerPorOrden($ordenId);
    }

    public function generarNumeroFactura(): string
    {
        $ultimaFactura = $this->repository->obtenerUltimaFactura();
        $numero = $ultimaFactura ? (int)substr($ultimaFactura->nro_factura, 4) + 1 : 1;
        return 'FAC-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }

    public function calcularTotales(array $items, float $porcentajeIgv): array
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['cantidad'] * $item['precio'];
        }

        $montoIgv = $subtotal * ($porcentajeIgv / 100);
        $total = $subtotal + $montoIgv;

        return [
            'subtotal' => $subtotal,
            'monto_igv' => $montoIgv,
            'total' => $total
        ];
    }

}
