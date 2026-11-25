@extends('admin.layouts.app')

@section('title', $customer->name)
@section('crumb', 'Customers')
@section('heading'){{ $customer->name }}@endsection
@section('actions')
    <a href="{{ route('admin.customers.index') }}" class="btn btn--ghost btn--sm"><x-admin.icon name="arrow-left" size="14" /> All customers</a>
@endsection

@section('content')
    <div class="stats">
        <div class="stat stat--brand"><p class="stat-label">Total spent</p><p class="stat-value">₹{{ number_format($spent) }}</p></div>
        <div class="stat"><p class="stat-label">Orders</p><p class="stat-value">{{ $orders->count() }}</p></div>
        <div class="stat"><p class="stat-label">Customer since</p><p class="stat-value" style="font-size: 1.6rem;">{{ $orders->last()->created_at->format('M Y') }}</p></div>
    </div>

    <div class="grid grid-form">
        <section class="card">
            <div class="card-head"><h2>Order <em>history</em></h2></div>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Order</th><th>Items</th><th>Status</th><th class="num">Total</th></tr></thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="link">{{ $order->number }}</a><div class="muted">{{ $order->created_at->format('d M Y') }}</div></td>
                                <td>{{ $order->items_count }}</td>
                                <td>@include('admin.partials.order-status', ['status' => $order->status])</td>
                                <td class="num strong">₹{{ number_format($order->total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <section class="card card-pad">
            <dl class="dl">
                <div><dt>Phone</dt><dd><a href="tel:{{ $customer->phone }}" class="link">{{ $customer->phone }}</a></dd></div>
                <div><dt>Email</dt><dd><a href="mailto:{{ $customer->email }}" class="link">{{ $customer->email }}</a></dd></div>
                <div><dt>Latest address</dt><dd>{{ $customer->address_line1 }} {{ $customer->address_line2 }}<br>{{ $customer->city }}, {{ $customer->state }} {{ $customer->pincode }}</dd></div>
            </dl>
        </section>
    </div>
@endsection
