<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\IBroadcastingService;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

readonly class PusherBroadcastingService implements IBroadcastingService
{
    private Pusher $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );
    }

    public function broadcast(string $channel, string $event, array $data): bool
    {
        try {
            // Verificar si el usuario tiene los datos necesarios
            $user = auth()->user();
            if (!$user) {
                Log::warning('Intento de broadcast sin usuario autenticado', [
                    'channel' => $channel,
                    'event' => $event
                ]);
                return false;
            }

            // Obtener los roles del usuario
            $roles = $user->roles->pluck('nombre')->toArray();
            
            // Si es superadmin, permitir broadcast en todos los canales
            if (in_array('superadmin', $roles)) {
                $this->pusher->trigger($channel, $event, $data);
                return true;
            }

            // Para otros roles, verificar tenant y sucursal
            $tenantId = $user->tenant_id;
            $sucursalId = $user->asignacionPersonal?->sucursal_id;

            // Si no tiene tenant_id, no permitir broadcast
            if (!$tenantId) {
                Log::warning('Intento de broadcast sin tenant_id', [
                    'user_id' => $user->id,
                    'channel' => $channel,
                    'event' => $event
                ]);
                return false;
            }

            // Construir el canal con los datos disponibles
            $channelName = "private-tenant.{$tenantId}";
            if ($sucursalId) {
                $channelName .= ".sucursal.{$sucursalId}";
            }
            $channelName .= ".{$channel}";

            $this->pusher->trigger($channelName, $event, $data);
            return true;

        } catch (\Exception $e) {
            Log::error('Error en broadcast de Pusher', [
                'error' => $e->getMessage(),
                'channel' => $channel,
                'event' => $event
            ]);
            return false;
        }
    }

    public function broadcastToPrivate(string $channel, string $event, array $data): void
    {
        try {
            $this->pusher->trigger('private-' . $channel, $event, $data);
        } catch (\Exception $e) {
            Log::error('Error al enviar broadcast privado:', [
                'channel' => $channel,
                'event' => $event,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function broadcastToPresence(string $channel, string $event, array $data): void
    {
        try {
            $this->pusher->trigger('presence-' . $channel, $event, $data);
        } catch (\Exception $e) {
            Log::error('Error al enviar broadcast de presencia:', [
                'channel' => $channel,
                'event' => $event,
                'error' => $e->getMessage()
            ]);
        }
    }
} 