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
 * @property int $tenant_id
 * @property int $grupo_restaurant_id
 * @property int $logo_id
 * @property string|null $nro_ruc
 * @property string|null $nombre_legal
 * @property string|null $email
 * @property string|null $direccion
 * @property numeric|null $latitud
 * @property numeric|null $longitud
 * @property string|null $tipo_negocio
 * @property string|null $sitio_web_url
 * @property string|null $telefono
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read GrupoRestaurantes $grupoRestaurantes
 * @property-read Imagen $logo
 * @property-read Tenant $tenant
 * @method static Builder<static>|Restaurante newModelQuery()
 * @method static Builder<static>|Restaurante newQuery()
 * @method static Builder<static>|Restaurante query()
 * @method static Builder<static>|Restaurante whereActivo($value)
 * @method static Builder<static>|Restaurante whereCreatedAt($value)
 * @method static Builder<static>|Restaurante whereDireccion($value)
 * @method static Builder<static>|Restaurante whereEmail($value)
 * @method static Builder<static>|Restaurante whereGrupoRestaurantId($value)
 * @method static Builder<static>|Restaurante whereId($value)
 * @method static Builder<static>|Restaurante whereLatitud($value)
 * @method static Builder<static>|Restaurante whereLogoId($value)
 * @method static Builder<static>|Restaurante whereLongitud($value)
 * @method static Builder<static>|Restaurante whereNombreLegal($value)
 * @method static Builder<static>|Restaurante whereNroRuc($value)
 * @method static Builder<static>|Restaurante whereSitioWebUrl($value)
 * @method static Builder<static>|Restaurante whereTelefono($value)
 * @method static Builder<static>|Restaurante whereTenantId($value)
 * @method static Builder<static>|Restaurante whereTipoNegocio($value)
 * @method static Builder<static>|Restaurante whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Restaurante extends Model
{
    protected $table = 'restaurantes';

    protected $fillable = [
        'tenant_id',
        'grupo_restaurant_id',
        'logo_id',
        'nro_ruc',
        'nombre_legal',
        'email',
        'direccion',
        'latitud',
        'longitud',
        'tipo_negocio',
        'sitio_web_url',
        'telefono',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function grupoRestaurantes(): BelongsTo
    {
        return $this->belongsTo(GrupoRestaurantes::class, 'grupo_restaurant_id');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'logo_id');
    }
}



