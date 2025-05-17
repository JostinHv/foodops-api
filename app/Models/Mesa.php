<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mesa extends Model
{
    protected $table = 'mesas';

    protected $fillable = [
        'estado_mesa_id',
        'sucursal_id',
        'nombre',
        'capacidad'
    ];

    protected $casts = [
        'estado_mesa_id' => 'integer',
        'sucursal_id' => 'integer',
        'nombre' => 'string',
        'capacidad' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function estadoMesa(): BelongsTo
    {
        return $this->belongsTo(EstadoMesa::class, 'estado_mesa_id');
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

}
