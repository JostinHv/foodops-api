<?php

namespace App\Helpers;

class EstadoOrdenHelper
{
    /**
     * Mapa de colores para los estados de órdenes
     * Los colores están basados en Bootstrap 5
     */
    private static array $estadoColores = [
        'Pendiente' => 'danger',      // Rojo - Estado inicial crítico
        'En Proceso' => 'warning',    // Amarillo - En preparación
        'Preparada' => 'info',        // Azul claro - Lista para servir
        'Servida' => 'primary',       // Azul - Ya servida
        'Solicitando Pago' => 'primary', // Azul - Esperando pago
        'Pagada' => 'success',        // Verde - Completada exitosamente
        'Cancelada' => 'danger',      // Rojo - Cancelada
        'En disputa' => 'danger',     // Rojo - Problema
        'Cerrada' => 'secondary',     // Gris - Finalizada
    ];

    /**
     * Obtener el color de un estado específico
     */
    public static function getColor(string $estadoNombre): string
    {
        return self::$estadoColores[$estadoNombre] ?? 'secondary';
    }

    /**
     * Obtener el color de un estado con prefijo bg-
     */
    public static function getBgColor(string $estadoNombre): string
    {
        return 'bg-' . self::getColor($estadoNombre);
    }

    /**
     * Obtener el color de un estado con prefijo text-
     */
    public static function getTextColor(string $estadoNombre): string
    {
        return 'text-' . self::getColor($estadoNombre);
    }

    /**
     * Obtener el color de un estado con prefijo border-
     */
    public static function getBorderColor(string $estadoNombre): string
    {
        return 'border-' . self::getColor($estadoNombre);
    }

    /**
     * Obtener el color de un estado con prefijo btn-outline-
     */
    public static function getOutlineColor(string $estadoNombre): string
    {
        return 'btn-outline-' . self::getColor($estadoNombre);
    }

    /**
     * Obtener el color de un estado con prefijo btn-
     */
    public static function getButtonColor(string $estadoNombre): string
    {
        return 'btn-' . self::getColor($estadoNombre);
    }

    /**
     * Obtener todos los colores disponibles
     */
    public static function getAllColors(): array
    {
        return self::$estadoColores;
    }

    /**
     * Verificar si un estado existe
     */
    public static function estadoExiste(string $estadoNombre): bool
    {
        return array_key_exists($estadoNombre, self::$estadoColores);
    }

    /**
     * Obtener el color para un objeto de estado (si tiene propiedad nombre)
     */
    public static function getColorFromObject($estado): string
    {
        if (is_object($estado) && property_exists($estado, 'nombre')) {
            return self::getColor($estado->nombre);
        }

        if (is_string($estado)) {
            return self::getColor($estado);
        }

        return 'secondary';
    }

    /**
     * Obtener el color bg para un objeto de estado
     */
    public static function getBgColorFromObject($estado): string
    {
        return 'bg-' . self::getColorFromObject($estado);
    }
}
