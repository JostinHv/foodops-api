<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends Model
{
    protected $table = 'tenants';

    protected $fillable = [
        'logo_id',
        'dominio',
        'datos_contacto',
        'activo'
    ];

    protected $casts = [
        'logo_id' => 'integer',
        'dominio' => 'string',
        'datos_contacto' => 'json',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'logo_id');
    }

}
