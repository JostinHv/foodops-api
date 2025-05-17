<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionPersonal extends Model
{
    protected $table = 'asignaciones_personal';

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'usuario_id',
        'sucursal_id',
        'tipo',
        'notas',
        'fecha_asignacion',
        'fecha_fin',
        'activo'
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'usuario_id' => 'integer',
        'sucursal_id' => 'integer',
        'tipo' => 'string',
        'notas' => 'string',
        'fecha_asignacion' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
