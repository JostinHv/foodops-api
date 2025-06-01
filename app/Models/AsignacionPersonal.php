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
 * @property int $tenant_id
 * @property int $usuario_id
 * @property int $sucursal_id
 * @property string|null $tipo
 * @property string|null $notas
 * @property Carbon|null $fecha_asignacion
 * @property Carbon|null $fecha_fin
 * @property bool $activo
 * @property-read Sucursal $sucursal
 * @property-read Tenant $tenant
 * @property-read Usuario $usuario
 * @method static Builder<static>|AsignacionPersonal newModelQuery()
 * @method static Builder<static>|AsignacionPersonal newQuery()
 * @method static Builder<static>|AsignacionPersonal query()
 * @method static Builder<static>|AsignacionPersonal whereActivo($value)
 * @method static Builder<static>|AsignacionPersonal whereFechaAsignacion($value)
 * @method static Builder<static>|AsignacionPersonal whereFechaFin($value)
 * @method static Builder<static>|AsignacionPersonal whereId($value)
 * @method static Builder<static>|AsignacionPersonal whereNotas($value)
 * @method static Builder<static>|AsignacionPersonal whereSucursalId($value)
 * @method static Builder<static>|AsignacionPersonal whereTenantId($value)
 * @method static Builder<static>|AsignacionPersonal whereTipo($value)
 * @method static Builder<static>|AsignacionPersonal whereUsuarioId($value)
 * @mixin Eloquent
 */
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
        'fecha_asignacion' => 'datetime',
        'fecha_fin' => 'datetime',
        'activo' => 'boolean'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }
}
