<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSuscripcion extends Model
{
    protected $table = 'planes_suscripciones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'intervalo',
        'caracteristicas',
        'activo'
    ];

    protected $casts = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'precio' => 'decimal:2',
        'intervalo' => 'string',
        'caracteristicas' => 'json',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


}
