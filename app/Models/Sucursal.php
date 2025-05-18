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
 * @property int $restaurante_id
 * @property int $usuario_id
 * @property string|null $nombre
 * @property string|null $tipo
 * @property numeric|null $latitud
 * @property numeric|null $longitud
 * @property string|null $direccion
 * @property string|null $telefono
 * @property string|null $email
 * @property int|null $capacidad_total
 * @property Carbon|null $hora_apertura
 * @property Carbon|null $hora_cierre
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Restaurante $restaurante
 * @property-read Usuario $usuario
 * @method static Builder<static>|Sucursal newModelQuery()
 * @method static Builder<static>|Sucursal newQuery()
 * @method static Builder<static>|Sucursal query()
 * @method static Builder<static>|Sucursal whereActivo($value)
 * @method static Builder<static>|Sucursal whereCapacidadTotal($value)
 * @method static Builder<static>|Sucursal whereCreatedAt($value)
 * @method static Builder<static>|Sucursal whereDireccion($value)
 * @method static Builder<static>|Sucursal whereEmail($value)
 * @method static Builder<static>|Sucursal whereHoraApertura($value)
 * @method static Builder<static>|Sucursal whereHoraCierre($value)
 * @method static Builder<static>|Sucursal whereId($value)
 * @method static Builder<static>|Sucursal whereLatitud($value)
 * @method static Builder<static>|Sucursal whereLongitud($value)
 * @method static Builder<static>|Sucursal whereNombre($value)
 * @method static Builder<static>|Sucursal whereRestauranteId($value)
 * @method static Builder<static>|Sucursal whereTelefono($value)
 * @method static Builder<static>|Sucursal whereTipo($value)
 * @method static Builder<static>|Sucursal whereUpdatedAt($value)
 * @method static Builder<static>|Sucursal whereUsuarioId($value)
 * @mixin Eloquent
 */
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
