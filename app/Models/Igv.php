<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Igv extends Model
{
    protected $table = 'igv';


    protected $fillable = [
        'anio',
        'valor_decimal',
        'valor_porcentaje',
        'activo',
    ];

    protected $casts = [
        'anio' => 'integer',
        'valor_decimal' => 'decimal:2',
        'valor_porcentaje' => 'decimal:2',
        'activo' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
