<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    protected $table = 'imagenes';

    protected $fillable = [
        'url',
        'activo',
    ];

    protected $casts = [
        'url' => 'string',
        'activo' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
