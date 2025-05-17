<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
}
