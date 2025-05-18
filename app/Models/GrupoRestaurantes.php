<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $tenant_id
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tenant $tenant
 * @method static Builder<static>|GrupoRestaurantes newModelQuery()
 * @method static Builder<static>|GrupoRestaurantes newQuery()
 * @method static Builder<static>|GrupoRestaurantes query()
 * @method static Builder<static>|GrupoRestaurantes whereCreatedAt($value)
 * @method static Builder<static>|GrupoRestaurantes whereDescripcion($value)
 * @method static Builder<static>|GrupoRestaurantes whereId($value)
 * @method static Builder<static>|GrupoRestaurantes whereNombre($value)
 * @method static Builder<static>|GrupoRestaurantes whereTenantId($value)
 * @method static Builder<static>|GrupoRestaurantes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GrupoRestaurantes extends Model
{
    protected $table = 'grupos_restaurantes';

    protected $fillable = [
        'tenant_id',
        'nombre',
        'descripcion'
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'nombre' => 'string',
        'descripcion' => 'string'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

}
