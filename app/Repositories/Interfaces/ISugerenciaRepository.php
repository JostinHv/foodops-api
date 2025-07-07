<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ISugerenciaRepository extends IBaseRepository
{
    // Métodos adicionales si se requieren en el futuro
    public function obtenerPorUsuarioId(int $usuarioId): \Illuminate\Database\Eloquent\Collection;
} 