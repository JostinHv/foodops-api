<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IEstadoCajaRepository
{
    public function obtenerTodos(): Collection;
    public function obtenerPorId(int $id): ?Model;
    public function crear(array $datos): Model;
    public function actualizar(int $id, array $datos): bool;
    public function eliminar(int $id): bool;
} 