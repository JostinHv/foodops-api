<?php

use App\Models\Usuario;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Canal para usuario específico
Broadcast::channel('App.Models.Usuario.{id}', function (Usuario $user, $id) {
    return $user->id === (int)$id;
});

// Canal genérico para órdenes
Broadcast::channel('ordenes', function ($user) {
    return true; // Cualquier usuario autenticado puede escuchar
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal para tenant específico
Broadcast::channel('tenant.{tenantId}.ordenes', function (Usuario $user, $tenantId) {
    Log::info('Autorizando canal tenant', [
        'user_id' => $user->id,
        'user_tenant_id' => $user->tenant_id,
        'requested_tenant_id' => $tenantId,
        'user_roles' => $user->roles->pluck('nombre')->toArray()
    ]);

    // Superadmin puede acceder a cualquier tenant
    if ($user->roles->contains('nombre', 'superadmin')) {
        return true;
    }

    // Para otros roles, verificar que pertenezcan al tenant
    return $user->tenant_id === (int)$tenantId;
});

// Canal para sucursal específica
Broadcast::channel('tenant.{tenantId}.sucursal.{sucursalId}.ordenes', static function ($user, $tenantId, $sucursalId) {
    Log::info('Autorizando canal sucursal', [
        'user_id' => $user->id,
        'user_tenant_id' => $user->tenant_id,
        'requested_tenant_id' => $tenantId,
        'requested_sucursal_id' => $sucursalId,
        'user_roles' => $user->roles->pluck('nombre')->toArray(),
        'has_asignacion' => $user->asignacionPersonal ? 'yes' : 'no',
        'user_sucursal_id' => $user->asignacionPersonal ? $user->asignacionPersonal->sucursal_id : 'null'
    ]);

    // Superadmin puede acceder a cualquier sucursal
    if ($user->roles->contains('nombre', 'superadmin')) {
        Log::info('Acceso permitido: Superadmin');
        return true;
    }

    // Para otros roles, verificar tenant y sucursal
    if ($user->tenant_id !== (int)$tenantId) {
        Log::warning('Acceso denegado: Tenant diferente', [
            'user_tenant' => $user->tenant_id,
            'requested_tenant' => $tenantId
        ]);
        return false;
    }

    // Si el usuario tiene asignación personal, verificar la sucursal
    if ($user->asignacionPersonal) {
        $hasAccess = $user->asignacionPersonal->sucursal_id === (int)$sucursalId;
        Log::info('Verificando sucursal con asignación personal', [
            'user_sucursal' => $user->asignacionPersonal->sucursal_id,
            'requested_sucursal' => $sucursalId,
            'access_granted' => $hasAccess
        ]);
        return $hasAccess;
    }

    // Si no tiene asignación personal pero es del mismo tenant, permitir acceso
    Log::info('Acceso permitido: Mismo tenant sin asignación personal');
    return true;
});
