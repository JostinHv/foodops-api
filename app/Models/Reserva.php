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
 * @property int $mesa_id
 * @property int $recepcionista_id
 * @property string|null $nombre_cliente
 * @property string|null $email_cliente
 * @property string|null $telefono_cliente
 * @property int|null $tamanio_grupo
 * @property Carbon|null $fecha_reserva
 * @property Carbon|null $hora_inicio
 * @property Carbon|null $hora_fin
 * @property string|null $notas
 * @property string|null $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Mesa $mesa
 * @property-read Usuario $recepcionista
 * @method static Builder<static>|Reserva newModelQuery()
 * @method static Builder<static>|Reserva newQuery()
 * @method static Builder<static>|Reserva query()
 * @method static Builder<static>|Reserva whereCreatedAt($value)
 * @method static Builder<static>|Reserva whereEmailCliente($value)
 * @method static Builder<static>|Reserva whereEstado($value)
 * @method static Builder<static>|Reserva whereFechaReserva($value)
 * @method static Builder<static>|Reserva whereHoraFin($value)
 * @method static Builder<static>|Reserva whereHoraInicio($value)
 * @method static Builder<static>|Reserva whereId($value)
 * @method static Builder<static>|Reserva whereMesaId($value)
 * @method static Builder<static>|Reserva whereNombreCliente($value)
 * @method static Builder<static>|Reserva whereNotas($value)
 * @method static Builder<static>|Reserva whereRecepcionistaId($value)
 * @method static Builder<static>|Reserva whereTamanioGrupo($value)
 * @method static Builder<static>|Reserva whereTelefonoCliente($value)
 * @method static Builder<static>|Reserva whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'mesa_id',
        'recepcionista_id',
        'nombre_cliente',
        'email_cliente',
        'telefono_cliente',
        'tamanio_grupo',
        'fecha_reserva',
        'hora_inicio',
        'hora_fin',
        'notas',
        'estado'
    ];

    protected $casts = [
        'mesa_id' => 'integer',
        'recepcionista_id' => 'integer',
        'nombre_cliente' => 'string',
        'email_cliente' => 'string',
        'telefono_cliente' => 'string',
        'tamanio_grupo' => 'integer',
        'fecha_reserva' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
        'notas' => 'string',
        'estado' => 'string'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class, 'mesa_id');
    }

    public function recepcionista(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'recepcionista_id');
    }
}
