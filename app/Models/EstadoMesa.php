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
 * @method static Builder<static>|EstadoMesa newModelQuery()
 * @method static Builder<static>|EstadoMesa newQuery()
 * @method static Builder<static>|EstadoMesa query()
 * @method static Builder<static>|EstadoMesa whereActivo($value)
 * @method static Builder<static>|EstadoMesa whereCreatedAt($value)
 * @method static Builder<static>|EstadoMesa whereDescripcion($value)
 * @method static Builder<static>|EstadoMesa whereId($value)
 * @method static Builder<static>|EstadoMesa whereNombre($value)
 * @method static Builder<static>|EstadoMesa whereUpdatedAt($value)
 * @mixin Eloquent
 */
class EstadoMesa extends Model
{
    protected $table = 'estados_mesas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
