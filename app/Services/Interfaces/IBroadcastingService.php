<?php

namespace App\Services\Interfaces;

interface IBroadcastingService
{
    /**
     * Envía un mensaje a través del servicio de broadcasting
     *
     * @param string $channel Nombre del canal
     * @param string $event Nombre del evento
     * @param array $data Datos a enviar
     * @return bool True si el broadcast fue exitoso, false en caso contrario
     */
    public function broadcast(string $channel, string $event, array $data): bool;

    /**
     * Envía un evento a un canal privado específico
     */
    public function broadcastToPrivate(string $channel, string $event, array $data): void;

    /**
     * Envía un evento a un canal de presencia específico
     */
    public function broadcastToPresence(string $channel, string $event, array $data): void;
} 