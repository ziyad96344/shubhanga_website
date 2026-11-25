@extends('admin.layouts.app')

@section('title', 'Orders')
@section('crumb', 'Sales')
@section('heading')All <em>orders</em>@endsection

@section('content')
    <div class="tabs">
        <a href="{{ route('admin.orders.index', request()->except(['status', 'page'])) }}" class="tab {{ request('status') ? '' : 'is-active' }}">All <b>{{ $counts->sum() }}</b></a>
        @foreach (\App\Models\Order::STATUS_LABELS as $key => $label)
            <a href="{{ route('admin.orders.index', ['status' => $key] + request()->except(['status', 'page'])) }}" class="tab {{ request('status') === $key ? 'is-active' : '' }}">{{ $label }} <b>{{ $counts[$key] ?? 0 }}</b></a>
        @endforeach
    </div>

    <form method="get" class="card card-pad toolbar">
        @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div class="filters">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Order number, name, phone or email" class="input">
            <select name="payment" class="select" data-autosubmit>
                <option value="">Any payment</option>
                <option value="cod" @selected(request('payment') === 'cod')>Cash on delivery</option>
                <option value="online" @selected(request('payment') === 'online')>Paid online</option>
            </select>
            <button type="submit" class="btn btn--ghost btn--sm"><x-admin.icon name="search" size="14" /> Search</button>
            @if (request()->hasAny(['search', 'payment']))<a href="{{ route('admin.orders.index', request()->only('status')) }}" class="link">Clear</a>@endif
        </div>
    </form>

    <section class="card">
        @if ($orders->isEmpty())
            <div class="empty"><h3>No orders here</h3><p>Orders placed on the website appear in this list.</p></div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Payment</th><th>Status</th><th class="num">Total</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="link">{{ $order->number }}</a><div class="muted">{{ $order->created_at->format('d M Y, g:i a') }}</div></td>
                                <td><span class="strong">{{ $order->name }}</span><div class="muted">{{ $order->phone }} · {{ $order->city }}</div></td>
                                <td>{{ $order->items_count }}</td>
                                <td>
                                    {{ $order->payment_method === 'cod' ? 'COD' : 'Online' }}
                                    <div><span class="badge {{ $order->payment_status === 'paid' ? 'badge--ok' : ($order->payment_status === 'failed' ? 'badge--brand' : 'badge--muted') }}">{{ ucfirst($order->payment_status) }}</span></div>
                                </td>
                                <td>@include('admin.partials.order-status', ['status' => $order->status])</td>
                                <td class="num strong">₹{{ number_format($order->total) }}</td>
                                <td><div class="row-actions"><a href="{{ route('admin.orders.show', $order) }}" class="icon-btn" title="Open"><x-admin.icon name="arrow-right" size="15" /></a></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('admin.partials.pager', ['paginator' => $orders])
    </section>
@endsection
