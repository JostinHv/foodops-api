<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int|null $anio
 * @property numeric|null $valor_decimal
 * @property numeric|null $valor_porcentaje
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|Igv newModelQuery()
 * @method static Builder<static>|Igv newQuery()
 * @method static Builder<static>|Igv query()
 * @method static Builder<static>|Igv whereActivo($value)
 * @method static Builder<static>|Igv whereAnio($value)
 * @method static Builder<static>|Igv whereCreatedAt($value)
 * @method static Builder<static>|Igv whereId($value)
 * @method static Builder<static>|Igv whereUpdatedAt($value)
 * @method static Builder<static>|Igv whereValorDecimal($value)
 * @method static Builder<static>|Igv whereValorPorcentaje($value)
 * @mixin \Eloquent
 */
class Igv extends Model
{
    protected $table = 'igv';


    protected $fillable = [
        'anio',
        'valor_decimal',
        'valor_porcentaje',
        'activo',
    ];

    protected $casts = [
        'anio' => 'integer',
        'valor_decimal' => 'decimal:2',
        'valor_porcentaje' => 'decimal:2',
        'activo' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
