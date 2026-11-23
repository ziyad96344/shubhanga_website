@extends('layouts.app', ['title' => 'Order '.$order->number.' | '.config('site.full_name'), 'noindex' => true])

@php
    $steps = ['placed', 'packed', 'shipped', 'delivered'];
    $reached = array_search($order->status, $steps, true);
@endphp

@section('content')
    @include('partials.page-head', [
        'crumbs' => [$justPlaced ? 'Order placed' : 'Track order' => null],
        'eyebrow' => 'Order '.$order->number,
        'title' => $justPlaced ? 'Thank you, <em>'.e(strtok($order->name, ' ')).'</em>' : 'Your <em>order</em>',
        'intro' => $justPlaced
            ? 'Your order is confirmed. We have sent the details to '.$order->email.'. Keep your order number to track it.'
            : 'Placed on '.$order->created_at->format('j F Y').'.',
    ])

    <section class="wrap order-page">
        <div class="order-main">
            @if ($order->status === 'cancelled')
                <div class="form-alert">This order was cancelled.</div>
            @elseif ($order->status === 'awaiting_payment')
                <div class="form-alert">This order is waiting for payment.</div>
            @else
                <ol class="order-steps" aria-label="Order progress">
                    @foreach ($steps as $i => $step)
                        <li class="{{ $reached !== false && $i <= $reached ? 'is-done' : '' }}">
                            <span class="order-dot" aria-hidden="true"></span>
                            {{ \App\Models\Order::STATUS_LABELS[$step] }}
                        </li>
                    @endforeach
                </ol>
            @endif

            <div class="order-box">
                <h2>Items</h2>
                <ul class="summary-items">
                    @foreach ($order->items as $item)
                        <li>
                            <span class="summary-thumb"><img src="{{ $item->image }}" alt=""><b>{{ $item->qty }}</b></span>
                            <span class="summary-name">{{ $item->name }}<small>{{ $item->meta }}</small></span>
                            <span>₹{{ number_format($item->line_total) }}</span>
                        </li>
                    @endforeach
                </ul>
                <dl class="summary-rows">
                    <div><dt>Subtotal</dt><dd>₹{{ number_format($order->subtotal) }}</dd></div>
                    <div><dt>Shipping</dt><dd>{{ $order->shipping ? '₹'.$order->shipping : 'Free' }}</dd></div>
                    <div class="summary-total"><dt>Total</dt><dd>₹{{ number_format($order->total) }}</dd></div>
                </dl>
            </div>
        </div>

        <aside class="order-side">
            <div class="aside-card">
                <h2>Delivery</h2>
                <p>{{ $order->name }}<br>{{ $order->address_line1 }}@if ($order->address_line2)<br>{{ $order->address_line2 }}@endif<br>{{ $order->city }}, {{ $order->state }} {{ $order->pincode }}</p>
                <p class="aside-muted">{{ $order->phone }}</p>
            </div>
            <div class="aside-card">
                <h2>Payment</h2>
                <p>{{ $order->payment_method === 'cod' ? 'Cash on delivery' : 'Paid online' }}</p>
                <p class="aside-muted">Status: {{ $order->statusLabel() }}</p>
            </div>
            <a href="{{ route('books.index') }}" class="btn">Continue shopping</a>
        </aside>
    </section>
@endsection
