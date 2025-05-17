<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
