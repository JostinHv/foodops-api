@props(['estado', 'size' => 'normal', 'showIcon' => false])

@php
    use App\Helpers\EstadoOrdenHelper;
    
    // Obtener el nombre del estado
    $estadoNombre = is_object($estado) ? $estado->nombre : $estado;
    
    // Obtener el color del estado
    $color = EstadoOrdenHelper::getColor($estadoNombre);
    $bgColor = EstadoOrdenHelper::getBgColor($estadoNombre);
    
    // Definir clases según el tamaño
    $sizeClasses = [
        'small' => 'badge-sm',
        'normal' => '',
        'large' => 'badge-lg',
        'xl' => 'fs-6 px-3 py-2'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? '';
    
    // Iconos para cada estado
    $iconos = [
        'Pendiente' => 'bi-clock',
        'En Proceso' => 'bi-gear',
        'Preparada' => 'bi-check-circle',
        'Servida' => 'bi-truck',
        'Solicitando Pago' => 'bi-credit-card',
        'Pagada' => 'bi-check-circle-fill',
        'Cancelada' => 'bi-x-circle',
        'En disputa' => 'bi-exclamation-triangle',
        'Cerrada' => 'bi-lock'
    ];
    
    $icono = $iconos[$estadoNombre] ?? 'bi-circle';
@endphp

<span class="badge {{ $bgColor }} {{ $sizeClass }} {{ $attributes->get('class') }}">
    @if($showIcon)
        <i class="bi {{ $icono }} me-1"></i>
    @endif
    {{ $estadoNombre }}
</span> 