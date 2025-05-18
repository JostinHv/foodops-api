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
 * @property int $restaurante_id
 * @property int $sucursal_id
 * @property int $mesa_id
 * @property int $estado_orden_id
 * @property int $mesero_id
 * @property int $cajero_id
 * @property string|null $nro_orden
 * @property string|null $nombre_cliente
 * @property string|null $peticiones_especiales
 * @property string|null $tipo_servicio
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Usuario $cajero
 * @property-read EstadoOrden $estadoOrden
 * @property-read Mesa $mesa
 * @property-read Usuario $mesero
 * @property-read Restaurante $restaurante
 * @property-read Sucursal $sucursal
 * @property-read Tenant $tenant
 * @method static Builder<static>|Orden newModelQuery()
 * @method static Builder<static>|Orden newQuery()
 * @method static Builder<static>|Orden query()
 * @method static Builder<static>|Orden whereCajeroId($value)
 * @method static Builder<static>|Orden whereCreatedAt($value)
 * @method static Builder<static>|Orden whereEstadoOrdenId($value)
 * @method static Builder<static>|Orden whereId($value)
 * @method static Builder<static>|Orden whereMesaId($value)
 * @method static Builder<static>|Orden whereMeseroId($value)
 * @method static Builder<static>|Orden whereNombreCliente($value)
 * @method static Builder<static>|Orden whereNroOrden($value)
 * @method static Builder<static>|Orden wherePeticionesEspeciales($value)
 * @method static Builder<static>|Orden whereRestauranteId($value)
 * @method static Builder<static>|Orden whereSucursalId($value)
 * @method static Builder<static>|Orden whereTenantId($value)
 * @method static Builder<static>|Orden whereTipoServicio($value)
 * @method static Builder<static>|Orden whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Orden extends Model
{
    protected $table = 'ordenes';

    protected $fillable = [
        'tenant_id',
        'restaurante_id',
        'sucursal_id',
        'mesa_id',
        'estado_orden_id',
        'mesero_id',
        'cajero_id',
        'nro_orden',
        'nombre_cliente',
        'peticiones_especiales',
        'tipo_servicio'
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'restaurante_id' => 'integer',
        'sucursal_id' => 'integer',
        'mesa_id' => 'integer',
        'estado_orden_id' => 'integer',
        'mesero_id' => 'integer',
        'cajero_id' => 'integer',
        'nro_orden' => 'string',
        'nombre_cliente' => 'string',
        'peticiones_especiales' => 'string',
        'tipo_servicio' => 'string'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class, 'mesa_id');
    }

    public function estadoOrden(): BelongsTo
    {
        return $this->belongsTo(EstadoOrden::class, 'estado_orden_id');
    }

    public function mesero(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'mesero_id');
    }

    public function cajero(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'cajero_id');
    }

}
