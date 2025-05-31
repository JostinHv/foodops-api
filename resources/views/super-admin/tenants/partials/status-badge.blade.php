@php
    $badgeClass = match($status) {
        'Active' => 'bg-success',
        'Pending' => 'bg-warning text-dark',
        'Inactive' => 'bg-danger',
        default => 'bg-secondary'
    };
@endphp
<span class="badge {{ $badgeClass }}">{{ $status }}</span>

