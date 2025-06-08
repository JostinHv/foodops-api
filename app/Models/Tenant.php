<?php

namespace App\Models;

use App\Traits\Auditable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int|null $logo_id
 * @property string $dominio
 * @property array<array-key, mixed>|null $datos_contacto
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Imagen|null $logo
 * @method static Builder<static>|Tenant newModelQuery()
 * @method static Builder<static>|Tenant newQuery()
 * @method static Builder<static>|Tenant query()
 * @method static Builder<static>|Tenant whereActivo($value)
 * @method static Builder<static>|Tenant whereCreatedAt($value)
 * @method static Builder<static>|Tenant whereDatosContacto($value)
 * @method static Builder<static>|Tenant whereDominio($value)
 * @method static Builder<static>|Tenant whereId($value)
 * @method static Builder<static>|Tenant whereLogoId($value)
 * @method static Builder<static>|Tenant whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Tenant extends Model
{
    use Auditable;

    protected $table = 'tenants';

    protected $fillable = [
        'logo_id',
        'dominio',
        'datos_contacto',
        'activo'
    ];

    protected $casts = [
        'logo_id' => 'integer',
        'dominio' => 'string',
        'datos_contacto' => 'json',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'logo_id');
    }


    public function suscripcion(): HasOne
    {
        return $this->hasOne(TenantSuscripcion::class, 'tenant_id')
            ->latest();
    }

}
