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
 * @property int $tenant_suscripcion_id
 * @property numeric|null $monto
 * @property Carbon|null $fecha_pago
 * @property string|null $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tenant $tenant
 * @property-read TenantSuscripcion $tenantSuscripcion
 * @method static Builder<static>|HistorialPagoSuscripcion newModelQuery()
 * @method static Builder<static>|HistorialPagoSuscripcion newQuery()
 * @method static Builder<static>|HistorialPagoSuscripcion query()
 * @method static Builder<static>|HistorialPagoSuscripcion whereCreatedAt($value)
 * @method static Builder<static>|HistorialPagoSuscripcion whereEstado($value)
 * @method static Builder<static>|HistorialPagoSuscripcion whereFechaPago($value)
 * @method static Builder<static>|HistorialPagoSuscripcion whereId($value)
 * @method static Builder<static>|HistorialPagoSuscripcion whereMonto($value)
 * @method static Builder<static>|HistorialPagoSuscripcion whereTenantId($value)
 * @method static Builder<static>|HistorialPagoSuscripcion whereTenantSuscripcionId($value)
 * @method static Builder<static>|HistorialPagoSuscripcion whereUpdatedAt($value)
 * @mixin Eloquent
 */
class HistorialPagoSuscripcion extends Model
{
    protected $table = 'historial_pagos_suscripciones';

    protected $fillable = [
        'tenant_id',
        'tenant_suscripcion_id',
        'monto',
        'fecha_pago',
        'estado'
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'tenant_suscripcion_id' => 'integer',
        'monto' => 'decimal:2',
        'fecha_pago' => 'date',
        'estado' => 'string'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function tenantSuscripcion(): BelongsTo
    {
        return $this->belongsTo(TenantSuscripcion::class, 'tenant_suscripcion_id');
    }
}
