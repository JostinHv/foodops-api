<?php

namespace App\Events;

use App\Models\Orden;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrdenEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Orden $orden;
    public string $tipo;
    public array $datosAdicionales;

    public function __construct(Orden $orden, string $tipo, array $datosAdicionales = [])
    {
        $this->orden = $orden;
        $this->tipo = $tipo;
        $this->datosAdicionales = $datosAdicionales;

        Log::info('OrdenEvent construido', [
            'orden_id' => $orden->id,
            'tipo' => $tipo,
            'datos_adicionales' => $datosAdicionales,
            'tenant_id' => $orden->tenant_id,
            'sucursal_id' => $orden->sucursal_id
        ]);
    }

    public function broadcastOn(): array
    {
        try {
            $channels = [];

            Log::info('Determinando canales de broadcast para OrdenEvent', [
                'orden_id' => $this->orden->id,
                'tenant_id' => $this->orden->tenant_id,
                'sucursal_id' => $this->orden->sucursal_id,
                'tipo' => $this->tipo
            ]);

            // Canal base para el tenant
            if ($this->orden->tenant_id) {
                $channels[] = new PrivateChannel("tenant.{$this->orden->tenant_id}.ordenes");
                Log::info('Agregado canal de tenant', [
                    'canal' => "tenant.{$this->orden->tenant_id}.ordenes"
                ]);
            }

            // Canal específico para la sucursal si existe
            if ($this->orden->sucursal_id) {
                $channels[] = new PrivateChannel("tenant.{$this->orden->tenant_id}.sucursal.{$this->orden->sucursal_id}.ordenes");
                Log::info('Agregado canal de sucursal', [
                    'canal' => "tenant.{$this->orden->tenant_id}.sucursal.{$this->orden->sucursal_id}.ordenes"
                ]);
            }

            // Si no hay canales válidos, crear un canal genérico
            if (empty($channels)) {
                Log::warning('Orden sin tenant_id o sucursal_id', [
                    'orden_id' => $this->orden->id,
                    'tipo' => $this->tipo
                ]);
                $channels[] = new PrivateChannel('ordenes');
            }

            Log::info('Canales de broadcast determinados', [
                'canales' => array_map(fn($channel) => $channel->name, $channels)
            ]);

            return $channels;
        } catch (\Exception $e) {
            Log::error('Error al determinar canales de broadcast', [
                'error' => $e->getMessage(),
                'orden_id' => $this->orden->id,
                'tipo' => $this->tipo
            ]);
            return [new PrivateChannel('ordenes')];
        }
    }

    public function broadcastAs(): string
    {
        Log::info('Determinando nombre del evento de broadcast', [
            'tipo' => $this->tipo,
            'orden_id' => $this->orden->id
        ]);
        return "orden.{$this->tipo}";
    }

    public function broadcastWith(): array
    {
        $data = [
            'orden' => [
                'id' => $this->orden->id,
                'nro_orden' => $this->orden->nro_orden,
                'estado' => $this->orden->estadoOrden->nombre,
                'mesa' => $this->orden->mesa->nombre ?? 'Sin mesa',
                'tenant_id' => $this->orden->tenant_id,
                'sucursal_id' => $this->orden->sucursal_id
            ],
            'datos_adicionales' => $this->datosAdicionales
        ];

        Log::info('OrdenEvent broadcastWith ejecutado', [
            'tipo' => $this->tipo,
            'datos_a_enviar' => $data,
            'canales' => $this->broadcastOn()
        ]);

        return $data;
    }
}
