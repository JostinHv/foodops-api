<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(static function ($model) {
            static::audit('INSERT', $model, null, $model->getAttributes());
        });

        static::updated(static function ($model) {
            $original = $model->getOriginal();
            $changes = $model->getChanges();
            static::audit('UPDATE', $model, $original, $changes);
        });

        static::deleted(static function ($model) {
            static::audit('DELETE', $model, $model->getOriginal(), null);
        });
    }

    protected static function audit($tipo, $model, $before, $after): void
    {
        // Verificar que el usuario esté autenticado
        $userId = Auth::check() ? Auth::id() : null;

        DB::table('movimientos_historial')->insert([
            'usuario_id' => $userId,
            'tipo' => $tipo,
            'tabla_modificada' => $model->getTable(),
            'valor_anterior' => $before ? json_encode($before) : null,
            'valor_actual' => $after ? json_encode($after) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
