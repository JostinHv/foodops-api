<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MovimientoCaja;

class TipoMovimientoCaja extends Model
{
    use HasFactory;

    protected $table = 'tipos_movimiento_caja';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function movimientosCaja()
    {
        return $this->hasMany(MovimientoCaja::class, 'tipo_movimiento_caja_id');
    }
} 