<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
