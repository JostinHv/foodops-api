<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IFacturaRepository;
use App\Services\Interfaces\IFacturaService;
use App\Services\Interfaces\ICajaService;
use App\Services\Interfaces\IAsignacionPersonalService;
use App\Services\Interfaces\IMovimientoCajaService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

readonly class FacturaService implements IFacturaService
{

    public function __construct(
        private IFacturaRepository $repository,
        private ICajaService $cajaService,
        private IAsignacionPersonalService $asignacionPersonalService,
        private IMovimientoCajaService $movimientoCajaService
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
        $factura = $this->repository->crear($datos);
        
        // Registrar movimiento de caja automáticamente
        $this->registrarMovimientoCaja($factura);
        
        return $factura;
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

    private function registrarMovimientoCaja(Model $factura): void
    {
        try {
            // Obtener la caja abierta de la sucursal
            $asignacion = $this->asignacionPersonalService->obtenerPorUsuarioId(Auth::id());
            if (!$asignacion) {
                return; // No hay asignación de sucursal
            }

            $cajaAbierta = $this->cajaService->obtenerAbiertaPorSucursal($asignacion->sucursal_id);
            if (!$cajaAbierta) {
                return; // No hay caja abierta
            }

            // Registrar el movimiento de venta
            $this->movimientoCajaService->registrarMovimiento([
                'caja_id' => $cajaAbierta->id,
                'factura_id' => $factura->id,
                'tipo_movimiento_caja_id' => 1, // VENTA
                'metodo_pago_id' => $factura->metodo_pago_id,
                'monto' => $factura->monto_total,
                'descripcion' => 'Venta - Factura ' . $factura->nro_factura,
                'usuario_id' => Auth::id()
            ]);
        } catch (\Exception $e) {
            // Log del error pero no fallar la creación de la factura
            \Log::error('Error al registrar movimiento de caja: ' . $e->getMessage());
        }
    }
}
