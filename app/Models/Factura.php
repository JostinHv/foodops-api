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
 * @property int $orden_id
 * @property int $metodo_pago_id
 * @property int $igv_id
 * @property string|null $nro_factura
 * @property numeric|null $monto_total
 * @property numeric|null $monto_total_igv
 * @property string|null $estado_pago
 * @property Carbon|null $fecha_pago
 * @property Carbon|null $hora_pago
 * @property string|null $notas
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Igv $igv
 * @property-read MetodoPago $metodoPago
 * @property-read Orden $orden
 * @method static Builder<static>|Factura newModelQuery()
 * @method static Builder<static>|Factura newQuery()
 * @method static Builder<static>|Factura query()
 * @method static Builder<static>|Factura whereCreatedAt($value)
 * @method static Builder<static>|Factura whereEstadoPago($value)
 * @method static Builder<static>|Factura whereFechaPago($value)
 * @method static Builder<static>|Factura whereHoraPago($value)
 * @method static Builder<static>|Factura whereId($value)
 * @method static Builder<static>|Factura whereIgvId($value)
 * @method static Builder<static>|Factura whereMetodoPagoId($value)
 * @method static Builder<static>|Factura whereMontoTotal($value)
 * @method static Builder<static>|Factura whereMontoTotalIgv($value)
 * @method static Builder<static>|Factura whereNotas($value)
 * @method static Builder<static>|Factura whereNroFactura($value)
 * @method static Builder<static>|Factura whereOrdenId($value)
 * @method static Builder<static>|Factura whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'orden_id',
        'metodo_pago_id',
        'igv_id',
        'nro_factura',
        'monto_total',
        'monto_total_igv',
        'estado_pago',
        'fecha_pago',
        'hora_pago',
        'notas'
    ];

    protected $casts = [
        'orden_id' => 'integer',
        'metodo_pago_id' => 'integer',
        'igv_id' => 'integer',
        'nro_factura' => 'string',
        'monto_total' => 'decimal:2',
        'monto_total_igv' => 'decimal:2',
        'estado_pago' => 'string',
        'fecha_pago' => 'date',
        'hora_pago' => 'date',
        'notas' => 'string'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class, 'orden_id');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function igv(): BelongsTo
    {
        return $this->belongsTo(Igv::class, 'igv_id');
    }
}
