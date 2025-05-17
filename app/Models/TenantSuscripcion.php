<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantSuscripcion extends Model
{
    protected $table = 'tenants_suscripciones';

    protected $fillable = [
        'plan_suscripcion_id',
        'metodo_pago_id',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'precio_acordado',
        'renovacion_automatica',
        'notas'
    ];

    protected $casts = [
        'plan_suscripcion_id' => 'integer',
        'metodo_pago_id' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'estado' => 'string',
        'precio_acordado' => 'decimal:2',
        'renovacion_automatica' => 'boolean',
        'notas' => 'string'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function planSuscripcion(): BelongsTo
    {
        return $this->belongsTo(PlanSuscripcion::class, 'plan_suscripcion_id');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

}
