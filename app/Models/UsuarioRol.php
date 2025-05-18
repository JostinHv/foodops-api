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
 * @property int $usuario_id
 * @property int $rol_id
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Rol $rol
 * @property-read Usuario $usuario
 * @method static Builder<static>|UsuarioRol newModelQuery()
 * @method static Builder<static>|UsuarioRol newQuery()
 * @method static Builder<static>|UsuarioRol query()
 * @method static Builder<static>|UsuarioRol whereActivo($value)
 * @method static Builder<static>|UsuarioRol whereCreatedAt($value)
 * @method static Builder<static>|UsuarioRol whereRolId($value)
 * @method static Builder<static>|UsuarioRol whereUpdatedAt($value)
 * @method static Builder<static>|UsuarioRol whereUsuarioId($value)
 * @mixin Eloquent
 */
class UsuarioRol extends Model
{
    protected $table = 'usuarios_roles';

    protected $fillable = [
        'usuario_id',
        'rol_id',
        'activo'
    ];

    protected $casts = [
        'usuario_id' => 'integer',
        'rol_id' => 'integer',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = null;
    public $incrementing = false;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

}
