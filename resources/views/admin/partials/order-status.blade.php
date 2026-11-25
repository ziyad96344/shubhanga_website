@php
    $tone = [
        'awaiting_payment' => 'muted', 'placed' => 'brand', 'packed' => 'info',
        'shipped' => 'warn', 'delivered' => 'ok', 'cancelled' => 'muted',
    ][$status] ?? 'muted';
@endphp
<span class="badge badge--{{ $tone }}">{{ \App\Models\Order::STATUS_LABELS[$status] ?? ucfirst($status) }}</span>
