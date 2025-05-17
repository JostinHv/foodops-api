<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
