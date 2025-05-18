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
 * @property int $categoria_menu_id
 * @property int $imagen_id
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property numeric|null $precio
 * @property int|null $orden_visualizacion
 * @property bool $disponible
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CategoriaMenu $categoriaMenu
 * @property-read Imagen $imagen
 * @method static Builder<static>|ItemMenu newModelQuery()
 * @method static Builder<static>|ItemMenu newQuery()
 * @method static Builder<static>|ItemMenu query()
 * @method static Builder<static>|ItemMenu whereActivo($value)
 * @method static Builder<static>|ItemMenu whereCategoriaMenuId($value)
 * @method static Builder<static>|ItemMenu whereCreatedAt($value)
 * @method static Builder<static>|ItemMenu whereDescripcion($value)
 * @method static Builder<static>|ItemMenu whereDisponible($value)
 * @method static Builder<static>|ItemMenu whereId($value)
 * @method static Builder<static>|ItemMenu whereImagenId($value)
 * @method static Builder<static>|ItemMenu whereNombre($value)
 * @method static Builder<static>|ItemMenu whereOrdenVisualizacion($value)
 * @method static Builder<static>|ItemMenu wherePrecio($value)
 * @method static Builder<static>|ItemMenu whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ItemMenu extends Model
{
    protected $table = 'items_menus';

    protected $fillable = [
        'categoria_menu_id',
        'imagen_id',
        'nombre',
        'descripcion',
        'precio',
        'orden_visualizacion',
        'disponible',
        'activo'
    ];

    protected $casts = [
        'categoria_menu_id' => 'integer',
        'imagen_id' => 'integer',
        'nombre' => 'string',
        'descripcion' => 'string',
        'precio' => 'decimal:2',
        'orden_visualizacion' => 'integer',
        'disponible' => 'boolean',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function categoriaMenu(): BelongsTo
    {
        return $this->belongsTo(CategoriaMenu::class, 'categoria_menu_id');
    }

    public function imagen(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'imagen_id');
    }

}
