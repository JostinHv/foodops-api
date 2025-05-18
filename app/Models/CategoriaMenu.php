<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $sucursal_id
 * @property int $imagen_id
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property int|null $orden_visualizacion
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Imagen $imagen
 * @property-read Sucursal $sucursal
 * @property-read Tenant $tenant
 * @method static Builder<static>|CategoriaMenu newModelQuery()
 * @method static Builder<static>|CategoriaMenu newQuery()
 * @method static Builder<static>|CategoriaMenu query()
 * @method static Builder<static>|CategoriaMenu whereActivo($value)
 * @method static Builder<static>|CategoriaMenu whereCreatedAt($value)
 * @method static Builder<static>|CategoriaMenu whereDescripcion($value)
 * @method static Builder<static>|CategoriaMenu whereId($value)
 * @method static Builder<static>|CategoriaMenu whereImagenId($value)
 * @method static Builder<static>|CategoriaMenu whereNombre($value)
 * @method static Builder<static>|CategoriaMenu whereOrdenVisualizacion($value)
 * @method static Builder<static>|CategoriaMenu whereSucursalId($value)
 * @method static Builder<static>|CategoriaMenu whereTenantId($value)
 * @method static Builder<static>|CategoriaMenu whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CategoriaMenu extends Model
{
    protected $table = 'categorias_menus';

    protected $fillable = [
        'tenant_id',
        'sucursal_id',
        'imagen_id',
        'nombre',
        'descripcion',
        'orden_visualizacion',
        'activo'
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'sucursal_id' => 'integer',
        'imagen_id' => 'integer',
        'nombre' => 'string',
        'descripcion' => 'string',
        'orden_visualizacion' => 'integer',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function imagen(): BelongsTo
    {
        return $this->belongsTo(Imagen::class);
    }

}
