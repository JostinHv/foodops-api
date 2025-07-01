<?php

namespace App\Repositories\Implementations;

use App\Models\ItemOrden;
use App\Repositories\Interfaces\IItemOrdenRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemOrdenRepository extends BaseRepository implements IItemOrdenRepository
{
    public function __construct(ItemOrden $modelo)
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
     * @throws \RuntimeException
     */
    public function crearItemsOrden(array $itemsOrden): bool
    {
        if (empty($itemsOrden)) {
            return false;
        }

        try {
            return DB::transaction(function () use ($itemsOrden) {
                $this->modelo->insert($itemsOrden);

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Error al crear items de orden', [
                'error' => $e->getMessage(),
                'items' => array_map(function ($item) {
                    return [
                        'orden_id' => $item['orden_id'] ?? null,
                        'item_menu_id' => $item['item_menu_id'] ?? null
                    ];
                }, $itemsOrden)
            ]);

            throw new \RuntimeException('Error al crear los items de la orden: ' . $e->getMessage());
        } catch (\Throwable $e) {
            Log::log('error', 'Error al crear items de orden');
        }
        return false;
    }
}
