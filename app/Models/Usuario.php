<?php

namespace App\Models;

use App\Traits\Auditable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
class Usuario extends Authenticatable implements JWTSubject
{
    protected $table = 'usuarios';

    use HasApiTokens, HasFactory, Notifiable;

    use Auditable;

    protected $fillable = [
        'tenant_id',
        'foto_perfil_id',
        'restaurante_id',
        'email',
        'password',
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
        'email' => 'string',
        'password' => 'string',
        'nombres' => 'string',
        'apellidos' => 'string',
        'celular' => 'string',
        'ultimo_acceso' => 'date',
        'activo' => 'boolean'
    ];

    protected $hidden = [
        'password',
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

    public function asignacionesPersonal(): HasMany
    {
        return $this->hasMany(AsignacionPersonal::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

}
