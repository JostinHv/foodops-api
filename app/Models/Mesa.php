<?php

namespace App\Models;

use App\Traits\Auditable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $estado_mesa_id
 * @property int $sucursal_id
 * @property string|null $nombre
 * @property int|null $capacidad
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read EstadoMesa $estadoMesa
 * @property-read Sucursal $sucursal
 * @method static Builder<static>|Mesa newModelQuery()
 * @method static Builder<static>|Mesa newQuery()
 * @method static Builder<static>|Mesa query()
 * @method static Builder<static>|Mesa whereCapacidad($value)
 * @method static Builder<static>|Mesa whereCreatedAt($value)
 * @method static Builder<static>|Mesa whereEstadoMesaId($value)
 * @method static Builder<static>|Mesa whereId($value)
 * @method static Builder<static>|Mesa whereNombre($value)
 * @method static Builder<static>|Mesa whereSucursalId($value)
 * @method static Builder<static>|Mesa whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Mesa extends Model
{
    protected $table = 'mesas';

    use Auditable;

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
