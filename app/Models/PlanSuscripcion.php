<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property numeric|null $precio
 * @property string|null $intervalo
 * @property array<array-key, mixed>|null $caracteristicas
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|PlanSuscripcion newModelQuery()
 * @method static Builder<static>|PlanSuscripcion newQuery()
 * @method static Builder<static>|PlanSuscripcion query()
 * @method static Builder<static>|PlanSuscripcion whereActivo($value)
 * @method static Builder<static>|PlanSuscripcion whereCaracteristicas($value)
 * @method static Builder<static>|PlanSuscripcion whereCreatedAt($value)
 * @method static Builder<static>|PlanSuscripcion whereDescripcion($value)
 * @method static Builder<static>|PlanSuscripcion whereId($value)
 * @method static Builder<static>|PlanSuscripcion whereIntervalo($value)
 * @method static Builder<static>|PlanSuscripcion whereNombre($value)
 * @method static Builder<static>|PlanSuscripcion wherePrecio($value)
 * @method static Builder<static>|PlanSuscripcion whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PlanSuscripcion extends Model
{
    protected $table = 'planes_suscripciones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'intervalo',
        'caracteristicas',
        'activo'
    ];

    protected $casts = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'precio' => 'decimal:2',
        'intervalo' => 'string',
        'caracteristicas' => 'json',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


}
