<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupoRestaurantes extends Model
{
    protected $table = 'grupos_restaurantes';

    protected $fillable = [
        'tenant_id',
        'nombre',
        'descripcion'
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'nombre' => 'string',
        'descripcion' => 'string'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

}
