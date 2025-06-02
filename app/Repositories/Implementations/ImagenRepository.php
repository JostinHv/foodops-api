<?php

namespace App\Repositories\Implementations;

use App\Models\Imagen;
use App\Repositories\Interfaces\IImagenRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;

class ImagenRepository extends ActivoBoolRepository implements IImagenRepository
{
    public function __construct(Imagen $modelo)
    {
        parent::__construct($modelo);
    }

    protected function aplicarFiltros(Builder $consulta, array $filtros): void
    {
        if (isset($filtros['url'])) {
            $consulta->where('url', 'like', '%' . $filtros['url'] . '%');
        }
        if (isset($filtros['activo'])) {
            $consulta->where('activo', $filtros['activo']);
        }
    }

    protected function aplicarBusqueda(Builder $consulta, ?string $searchTerm, ?string $searchColumn): void
    {
        if ($searchTerm) {
            if ($searchColumn) {
                $consulta->where($searchColumn, 'like', '%' . $searchTerm . '%');
            } else {
                $consulta->where('url', 'like', '%' . $searchTerm . '%');
            }
        }
    }

    protected function aplicarOrdenamiento(Builder $consulta, ?string $sortField, ?string $sortOrder): void
    {
        if ($sortField) {
            $consulta->orderBy($sortField, $sortOrder ?? 'asc');
        } else {
            $consulta->orderBy('id', 'desc');
        }
    }

    public function guardarImagen(UploadedFile $file, string $string)
    {
        $ruta = $file->storeAs('imagenes', $string, 'public');
        $datos = [
            'url' => $ruta,
            'activo' => true,
        ];
        return $this->crear($datos);
    }
}
