<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreCaja extends Model
{
    use HasFactory;

    protected $table = 'cierres_caja';

    protected $fillable = [
        'caja_id',
        'usuario_id',
        'fecha_cierre',
        'hora_cierre',
        'monto_efectivo_contado',
        'monto_tarjetas',
        'monto_transferencias',
        'monto_otros',
        'total_ventas',
        'total_retiros',
        'total_depositos',
        'total_gastos',
        'diferencia',
        'observaciones',
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
} 