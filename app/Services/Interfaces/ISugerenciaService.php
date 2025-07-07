<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ISugerenciaService extends IBaseService
{
    // Métodos adicionales si se requieren en el futuro
    public function obtenerPorUsuarioId(int $usuarioId): \Illuminate\Database\Eloquent\Collection;
} 