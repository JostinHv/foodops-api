<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table = 'movimientos_caja';

    protected $fillable = [
        'caja_id',
        'factura_id',
        'tipo_movimiento_caja_id',
        'metodo_pago_id',
        'monto',
        'descripcion',
        'usuario_id',
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function tipoMovimientoCaja()
    {
        return $this->belongsTo(TipoMovimientoCaja::class, 'tipo_movimiento_caja_id');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
} 