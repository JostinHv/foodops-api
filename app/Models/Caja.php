<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MovimientoCaja;
use App\Models\CierreCaja;

class Caja extends Model
{
    use HasFactory;

    protected $table = 'cajas';

    protected $fillable = [
        'sucursal_id',
        'usuario_id',
        'fecha_apertura',
        'hora_apertura',
        'monto_inicial',
        'fecha_cierre',
        'hora_cierre',
        'monto_final_esperado',
        'monto_final_real',
        'diferencia',
        'estado_caja_id',
        'observaciones',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function estadoCaja()
    {
        return $this->belongsTo(EstadoCaja::class, 'estado_caja_id');
    }

    public function movimientosCaja()
    {
        return $this->hasMany(MovimientoCaja::class, 'caja_id');
    }

    public function cierresCaja()
    {
        return $this->hasMany(CierreCaja::class, 'caja_id');
    }
} 