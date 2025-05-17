<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'restaurante_id',
        'usuario_id',
        'nombre',
        'tipo',
        'latitud',
        'longitud',
        'direccion',
        'telefono',
        'email',
        'capacidad_total',
        'hora_apertura',
        'hora_cierre',
        'activo'
    ];

    protected $casts = [
        'restaurante_id' => 'integer',
        'usuario_id' => 'integer',
        'nombre' => 'string',
        'tipo' => 'string',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'direccion' => 'string',
        'telefono' => 'string',
        'email' => 'string',
        'capacidad_total' => 'integer',
        'hora_apertura' => 'datetime',
        'hora_cierre' => 'datetime',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }


}
