@php
    $badgeClass = match($plan) {
        'Premium' => 'bg-success',
        'Estándar' => 'bg-primary',
        'Básico' => 'bg-secondary',
        default => 'bg-light'
    };
@endphp
<span class="badge {{ $badgeClass }}">{{ $plan }}</span>

