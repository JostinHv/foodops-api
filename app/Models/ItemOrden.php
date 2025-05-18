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
 * @property int $orden_id
 * @property int $item_menu_id
 * @property int $cantidad
 * @property numeric|null $monto
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ItemMenu $itemMenu
 * @property-read Orden $orden
 * @method static Builder<static>|ItemOrden newModelQuery()
 * @method static Builder<static>|ItemOrden newQuery()
 * @method static Builder<static>|ItemOrden query()
 * @method static Builder<static>|ItemOrden whereCantidad($value)
 * @method static Builder<static>|ItemOrden whereCreatedAt($value)
 * @method static Builder<static>|ItemOrden whereId($value)
 * @method static Builder<static>|ItemOrden whereItemMenuId($value)
 * @method static Builder<static>|ItemOrden whereMonto($value)
 * @method static Builder<static>|ItemOrden whereOrdenId($value)
 * @method static Builder<static>|ItemOrden whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ItemOrden extends Model
{
    protected $table = 'items_ordenes';

    protected $fillable = [
        'orden_id',
        'item_menu_id',
        'cantidad',
        'monto'
    ];

    protected $casts = [
        'orden_id' => 'integer',
        'item_menu_id' => 'integer',
        'cantidad' => 'integer',
        'monto' => 'decimal:2'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class, 'orden_id');
    }

    public function itemMenu(): BelongsTo
    {
        return $this->belongsTo(ItemMenu::class, 'item_menu_id');
    }
}
