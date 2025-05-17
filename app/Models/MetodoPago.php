<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodos_pagos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'activo' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
