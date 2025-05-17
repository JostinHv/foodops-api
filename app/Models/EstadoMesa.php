<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoMesa extends Model
{
    protected $table = 'estados_mesas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
