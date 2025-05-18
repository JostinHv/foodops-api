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
 * @method static Builder<static>|EstadoOrden newModelQuery()
 * @method static Builder<static>|EstadoOrden newQuery()
 * @method static Builder<static>|EstadoOrden query()
 * @method static Builder<static>|EstadoOrden whereActivo($value)
 * @method static Builder<static>|EstadoOrden whereCreatedAt($value)
 * @method static Builder<static>|EstadoOrden whereDescripcion($value)
 * @method static Builder<static>|EstadoOrden whereId($value)
 * @method static Builder<static>|EstadoOrden whereNombre($value)
 * @method static Builder<static>|EstadoOrden whereUpdatedAt($value)
 * @mixin Eloquent
 */
class EstadoOrden extends Model
{
    protected $table = 'estados_ordenes';

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

}



