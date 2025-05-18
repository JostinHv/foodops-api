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
 * @property string $nombre
 * @property string|null $descripcion
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|Rol newModelQuery()
 * @method static Builder<static>|Rol newQuery()
 * @method static Builder<static>|Rol query()
 * @method static Builder<static>|Rol whereActivo($value)
 * @method static Builder<static>|Rol whereCreatedAt($value)
 * @method static Builder<static>|Rol whereDescripcion($value)
 * @method static Builder<static>|Rol whereId($value)
 * @method static Builder<static>|Rol whereNombre($value)
 * @method static Builder<static>|Rol whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
