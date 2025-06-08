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
            $changes = $model->getChanges();

            if (empty($changes)) {
                return; // No hay cambios relevantes para auditar
            }

            $original = array_intersect_key($model->getOriginal(), $changes);

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

        // Función para enmascarar la contraseña
        $maskPassword = function ($data) use ($model) {
            if (is_array($data) && $model->getTable() === 'usuarios' && isset($data['password'])) {
                $data['password'] = '[ENMASCARADA]';
            }
            return $data;
        };

        // Aplicar enmascaramiento antes de guardar
        $before = $maskPassword($before);
        $after = $maskPassword($after);

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
