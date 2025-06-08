<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoHistorial extends Model
{
    protected $table = 'movimientos_historial';

    protected $fillable = [
        'usuario_id',
        'tipo',
        'tabla_modificada',
        'valor_anterior',
        'valor_actual'
    ];

    protected $casts = [
        'valor_anterior' => 'array',
        'valor_actual' => 'array'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }
} 