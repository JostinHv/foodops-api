<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string|null $url
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|Imagen newModelQuery()
 * @method static Builder<static>|Imagen newQuery()
 * @method static Builder<static>|Imagen query()
 * @method static Builder<static>|Imagen whereActivo($value)
 * @method static Builder<static>|Imagen whereCreatedAt($value)
 * @method static Builder<static>|Imagen whereId($value)
 * @method static Builder<static>|Imagen whereUpdatedAt($value)
 * @method static Builder<static>|Imagen whereUrl($value)
 * @mixin \Eloquent
 */
class Imagen extends Model
{
    protected $table = 'imagenes';

    protected $fillable = [
        'url',
        'activo',
    ];

    protected $casts = [
        'url' => 'string',
        'activo' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
