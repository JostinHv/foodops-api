<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Restaurante extends Model
{
    protected $table = 'restaurantes';

    protected $fillable = [
        'tenant_id',
        'grupo_restaurant_id',
        'logo_id',
        'nro_ruc',
        'nombre_legal',
        'email',
        'direccion',
        'latitud',
        'longitud',
        'tipo_negocio',
        'sitio_web_url',
        'telefono',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function grupoRestaurantes(): BelongsTo
    {
        return $this->belongsTo(GrupoRestaurantes::class, 'grupo_restaurant_id');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'logo_id');
    }
}



