<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
