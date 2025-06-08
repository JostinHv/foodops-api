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
 * @property int $plan_suscripcion_id
 * @property int $metodo_pago_id
 * @property Carbon|null $fecha_inicio
 * @property Carbon|null $fecha_fin
 * @property string $estado
 * @property numeric|null $precio_acordado
 * @property bool $renovacion_automatica
 * @property string|null $notas
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MetodoPago $metodoPago
 * @property-read PlanSuscripcion $planSuscripcion
 * @method static Builder<static>|TenantSuscripcion newModelQuery()
 * @method static Builder<static>|TenantSuscripcion newQuery()
 * @method static Builder<static>|TenantSuscripcion query()
 * @method static Builder<static>|TenantSuscripcion whereCreatedAt($value)
 * @method static Builder<static>|TenantSuscripcion whereEstado($value)
 * @method static Builder<static>|TenantSuscripcion whereFechaFin($value)
 * @method static Builder<static>|TenantSuscripcion whereFechaInicio($value)
 * @method static Builder<static>|TenantSuscripcion whereId($value)
 * @method static Builder<static>|TenantSuscripcion whereMetodoPagoId($value)
 * @method static Builder<static>|TenantSuscripcion whereNotas($value)
 * @method static Builder<static>|TenantSuscripcion wherePlanSuscripcionId($value)
 * @method static Builder<static>|TenantSuscripcion wherePrecioAcordado($value)
 * @method static Builder<static>|TenantSuscripcion whereRenovacionAutomatica($value)
 * @method static Builder<static>|TenantSuscripcion whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TenantSuscripcion extends Model
{
    protected $table = 'tenants_suscripciones';

    protected $fillable = [
        'tenant_id',
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
        'tenant_id' => 'integer',
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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');




    }

}
