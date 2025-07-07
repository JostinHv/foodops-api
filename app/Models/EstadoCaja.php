<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Caja;

class EstadoCaja extends Model
{
    use HasFactory;

    protected $table = 'estados_caja';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function cajas()
    {
        return $this->hasMany(Caja::class, 'estado_caja_id');
    }
} 