<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'created_at',
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
