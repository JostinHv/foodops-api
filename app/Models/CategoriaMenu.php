<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoriaMenu extends Model
{
    protected $table = 'categorias_menus';

    protected $fillable = [
        'tenant_id',
        'sucursal_id',
        'imagen_id',
        'nombre',
        'descripcion',
        'orden_visualizacion',
        'activo'
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'sucursal_id' => 'integer',
        'imagen_id' => 'integer',
        'nombre' => 'string',
        'descripcion' => 'string',
        'orden_visualizacion' => 'integer',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function imagen(): BelongsTo
    {
        return $this->belongsTo(Imagen::class);
    }

}
