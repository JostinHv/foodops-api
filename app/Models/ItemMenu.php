<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
