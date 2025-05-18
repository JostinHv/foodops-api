<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|MetodoPago newModelQuery()
 * @method static Builder<static>|MetodoPago newQuery()
 * @method static Builder<static>|MetodoPago query()
 * @method static Builder<static>|MetodoPago whereActivo($value)
 * @method static Builder<static>|MetodoPago whereCreatedAt($value)
 * @method static Builder<static>|MetodoPago whereDescripcion($value)
 * @method static Builder<static>|MetodoPago whereId($value)
 * @method static Builder<static>|MetodoPago whereNombre($value)
 * @method static Builder<static>|MetodoPago whereUpdatedAt($value)
 * @mixin Eloquent
 */
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
