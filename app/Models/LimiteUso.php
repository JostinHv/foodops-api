<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $tenant_suscripcion_id
 * @property string|null $tipo_recurso
 * @property int|null $limite_maximo
 * @property int|null $uso_actual
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TenantSuscripcion $tenantSuscripcion
 * @method static Builder<static>|LimiteUso newModelQuery()
 * @method static Builder<static>|LimiteUso newQuery()
 * @method static Builder<static>|LimiteUso query()
 * @method static Builder<static>|LimiteUso whereCreatedAt($value)
 * @method static Builder<static>|LimiteUso whereId($value)
 * @method static Builder<static>|LimiteUso whereLimiteMaximo($value)
 * @method static Builder<static>|LimiteUso whereTenantSuscripcionId($value)
 * @method static Builder<static>|LimiteUso whereTipoRecurso($value)
 * @method static Builder<static>|LimiteUso whereUpdatedAt($value)
 * @method static Builder<static>|LimiteUso whereUsoActual($value)
 * @mixin Eloquent
 */
class LimiteUso extends Model
{
    protected $table = 'limites_usos';

    protected $fillable = [
        'tenant_suscripcion_id',
        'tipo_recurso',
        'limite_maximo',
        'uso_actual'
    ];

    protected $casts = [
        'tenant_suscripcion_id' => 'integer',
        'tipo_recurso' => 'string',
        'limite_maximo' => 'integer',
        'uso_actual' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tenantSuscripcion(): BelongsTo
    {
        return $this->belongsTo(TenantSuscripcion::class, 'tenant_suscripcion_id');
    }

}
