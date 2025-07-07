<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\ICajaRepository;
use App\Services\Interfaces\ICajaService;
use App\Services\Interfaces\IMovimientoCajaService;
use App\Services\Interfaces\ICierreCajaService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

readonly class CajaService implements ICajaService
{
    public function __construct(
        private ICajaRepository $repository,
        private IMovimientoCajaService $movimientoCajaService,
        private ICierreCajaService $cierreCajaService
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

    public function obtenerPorSucursal(int $sucursalId): Collection
    {
        return $this->repository->obtenerPorSucursal($sucursalId);
    }

    public function obtenerAbiertaPorSucursal(int $sucursalId): ?Model
    {
        return $this->repository->obtenerAbiertaPorSucursal($sucursalId);
    }

    public function abrirCaja(array $datos): Model
    {
        return DB::transaction(function () use ($datos) {
            // Verificar que no haya una caja abierta en la sucursal
            $cajaAbierta = $this->obtenerAbiertaPorSucursal($datos['sucursal_id']);
            if ($cajaAbierta) {
                throw new \Exception('Ya existe una caja abierta en esta sucursal');
            }

            // Obtener el estado "ABIERTA" (ID 1 según la migración)
            $datos['estado_caja_id'] = 1;
            $datos['usuario_id'] = Auth::id();
            $datos['fecha_apertura'] = Carbon::now()->toDateString();
            $datos['hora_apertura'] = Carbon::now()->toTimeString();

            $caja = $this->crear($datos);

            // Registrar el movimiento inicial
            $this->movimientoCajaService->registrarMovimiento([
                'caja_id' => $caja->id,
                'tipo_movimiento_caja_id' => 3, // DEPOSITO
                'metodo_pago_id' => 1, // Efectivo
                'monto' => $datos['monto_inicial'],
                'descripcion' => 'Apertura de caja',
                'usuario_id' => Auth::id()
            ]);

            return $caja;
        });
    }

    public function cerrarCaja(int $cajaId, array $datos): Model
    {
        return DB::transaction(function () use ($cajaId, $datos) {
            $caja = $this->obtenerPorId($cajaId);
            if (!$caja) {
                throw new \Exception('Caja no encontrada');
            }

            // Calcular montos esperados
            $montoFinalEsperado = $this->calcularMontoFinalEsperado($cajaId);
            $totalesCierre = $this->cierreCajaService->calcularTotalesCierre($cajaId);

            // Actualizar la caja
            $this->actualizar($cajaId, [
                'fecha_cierre' => Carbon::now()->toDateString(),
                'hora_cierre' => Carbon::now()->toTimeString(),
                'monto_final_esperado' => $montoFinalEsperado,
                'monto_final_real' => $datos['monto_efectivo_contado'] + $datos['monto_tarjetas'] + $datos['monto_transferencias'] + $datos['monto_otros'],
                'diferencia' => ($datos['monto_efectivo_contado'] + $datos['monto_tarjetas'] + $datos['monto_transferencias'] + $datos['monto_otros']) - $montoFinalEsperado,
                'estado_caja_id' => 2, // CERRADA
                'observaciones' => $datos['observaciones'] ?? null
            ]);

            // Registrar el cierre
            $datos['caja_id'] = $cajaId;
            $datos['usuario_id'] = Auth::id();
            $datos['fecha_cierre'] = Carbon::now()->toDateString();
            $datos['hora_cierre'] = Carbon::now()->toTimeString();
            $datos = array_merge($datos, $totalesCierre);

            $this->cierreCajaService->registrarCierre($datos);

            return $caja;
        });
    }

    public function calcularMontoFinalEsperado(int $cajaId): float
    {
        $movimientos = $this->movimientoCajaService->obtenerPorCaja($cajaId);
        
        $total = 0;
        foreach ($movimientos as $movimiento) {
            switch ($movimiento->tipo_movimiento_caja_id) {
                case 1: // VENTA
                case 3: // DEPOSITO
                    $total += $movimiento->monto;
                    break;
                case 2: // RETIRO
                case 4: // GASTO
                    $total -= $movimiento->monto;
                    break;
            }
        }
        
        return round($total, 2);
    }

    public function obtenerMovimientosRecientes(int $cajaId, int $limite = 10): Collection
    {
        return $this->movimientoCajaService->obtenerPorCaja($cajaId)
            ->sortByDesc('created_at')
            ->take($limite);
    }
} 