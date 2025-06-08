<?php

namespace App\Repositories\Implementations;

use App\Models\LimiteUso;
use App\Repositories\Interfaces\ILimiteUsoRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LimiteUsoRepository extends BaseRepository implements ILimiteUsoRepository
{
    public function __construct(LimiteUso $modelo)
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
    public function crearLimiteUsoUsuario(int $tenantSuscriptionId, int $limiteMax): ?Model
    {
        $limiteUso = [
            'tenant_suscripcion_id' => $tenantSuscriptionId,
            'tipo_recurso' => 'usuario',
            'limite_maximo' => $limiteMax,
            'uso_actual' => 0,
        ];

        return $this->crear($limiteUso);
    }

    /**
     * @throws \Throwable
     */
    public function crearLimiteUsoRestaurante(int $tenantSuscriptionId, int $limiteMax): ?Model
    {
        $limiteUso = [
            'tenant_suscripcion_id' => $tenantSuscriptionId,
            'tipo_recurso' => 'restaurante',
            'limite_maximo' => $limiteMax,
            'uso_actual' => 0,
        ];

        return $this->crear($limiteUso);
    }

    /**
     * @throws \Throwable
     */
    public function crearLimiteUsoSucursal(int $tenantSuscriptionId, int $limiteMax): ?Model
    {
        $limiteUso = [
            'tenant_suscripcion_id' => $tenantSuscriptionId,
            'tipo_recurso' => 'sucursal',
            'limite_maximo' => $limiteMax,
            'uso_actual' => 0,
        ];

        return $this->crear($limiteUso);
    }

    /**
     * @throws \Throwable
     */
    public function modificarLimiteUsoUsuario(int $tenantSuscriptionId, array $datos): bool
    {
        $limiteUso = $this->modelo->select('id')->where('tenant_suscripcion_id', $tenantSuscriptionId)
            ->where('tipo_recurso', 'usuario')
            ->first();
        return $this->actualizar($limiteUso->id, $datos);
    }

    /**
     * @throws \Throwable
     */
    public function modificarLimiteUsoRestaurante(int $tenantSuscriptionId, array $datos): bool
    {
        $limiteUso = $this->modelo->select('id')->where('tenant_suscripcion_id', $tenantSuscriptionId)
            ->where('tipo_recurso', 'restaurante')
            ->first();
        return $this->actualizar($limiteUso->id, $datos);
    }

    /**
     * @throws \Throwable
     */
    public function modificarLimiteUsoSucursal(int $tenantSuscriptionId, array $datos): bool
    {
        $limiteUso = $this->modelo->select('id')->where('tenant_suscripcion_id', $tenantSuscriptionId)
            ->where('tipo_recurso', 'sucursal')
            ->first();
        return $this->actualizar($limiteUso->id, $datos);
    }

    public function estaElUsoAlMaximoDelRecurso(int $tenantSuscripcion, string $tipoRecurso): bool
    {
        $limiteUso = $this->modelo->where('tenant_suscripcion_id', $tenantSuscripcion)
            ->where('tipo_recurso', $tipoRecurso)
            ->first();

        if (!$limiteUso) {
            return false; // No existe el límite de uso para este recurso
        }

        return $limiteUso->uso_actual >= $limiteUso->limite_maximo;
    }
}
