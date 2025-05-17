<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
