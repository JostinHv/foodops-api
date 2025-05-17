<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LimiteUso extends Model
{
    protected $table = 'limites_usos';

    protected $fillable = [
        'tenant_suscripcion_id',
        'tipo_recurso',
        'limite_maximo',
        'uso_actual'
    ];

    protected $casts = [
        'tenant_suscripcion_id' => 'integer',
        'tipo_recurso' => 'string',
        'limite_maximo' => 'integer',
        'uso_actual' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tenantSuscripcion(): BelongsTo
    {
        return $this->belongsTo(TenantSuscripcion::class, 'tenant_suscripcion_id');
    }

}
