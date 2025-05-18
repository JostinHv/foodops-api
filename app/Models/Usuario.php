<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $tenant_id
 * @property int|null $foto_perfil_id
 * @property int|null $restaurante_id
 * @property string $nombre_usuario
 * @property string $email
 * @property string $hash_contrasenia
 * @property string|null $nombres
 * @property string|null $apellidos
 * @property string|null $celular
 * @property Carbon|null $ultimo_acceso
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Imagen|null $fotoPerfil
 * @property-read Restaurante|null $restaurante
 * @property-read Collection<int, Rol> $roles
 * @property-read int|null $roles_count
 * @property-read Tenant $tenant
 * @method static Builder<static>|Usuario newModelQuery()
 * @method static Builder<static>|Usuario newQuery()
 * @method static Builder<static>|Usuario query()
 * @method static Builder<static>|Usuario whereActivo($value)
 * @method static Builder<static>|Usuario whereApellidos($value)
 * @method static Builder<static>|Usuario whereCelular($value)
 * @method static Builder<static>|Usuario whereCreatedAt($value)
 * @method static Builder<static>|Usuario whereEmail($value)
 * @method static Builder<static>|Usuario whereFotoPerfilId($value)
 * @method static Builder<static>|Usuario whereHashContrasenia($value)
 * @method static Builder<static>|Usuario whereId($value)
 * @method static Builder<static>|Usuario whereNombreUsuario($value)
 * @method static Builder<static>|Usuario whereNombres($value)
 * @method static Builder<static>|Usuario whereRestauranteId($value)
 * @method static Builder<static>|Usuario whereTenantId($value)
 * @method static Builder<static>|Usuario whereUltimoAcceso($value)
 * @method static Builder<static>|Usuario whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'tenant_id',
        'foto_perfil_id',
        'restaurante_id',
        'nombre_usuario',
        'email',
        'hash_contrasenia',
        'nombres',
        'apellidos',
        'celular',
        'ultimo_acceso',
        'activo'
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'foto_perfil_id' => 'integer',
        'restaurante_id' => 'integer',
        'nombre_usuario' => 'string',
        'email' => 'string',
        'hash_contrasenia' => 'string',
        'nombres' => 'string',
        'apellidos' => 'string',
        'celular' => 'string',
        'ultimo_acceso' => 'date',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'hash_contrasenia',
        'created_at',
        'updated_at'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function fotoPerfil(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'foto_perfil_id');
    }

    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'usuarios_roles', 'usuario_id', 'rol_id');
    }

}
