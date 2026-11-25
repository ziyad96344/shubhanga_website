@extends('admin.layouts.app')

@section('title', 'Order '.$order->number)
@section('crumb', 'Orders')
@section('heading')Order <em>{{ $order->number }}</em>@endsection
@section('actions')
    <a href="{{ route('admin.orders.index') }}" class="btn btn--ghost btn--sm"><x-admin.icon name="arrow-left" size="14" /> All orders</a>
@endsection

@section('content')
    <div class="grid grid-form">
        <div class="grid">
            <section class="card">
                <div class="card-head">
                    <h2>Books <em>ordered</em></h2>
                    @include('admin.partials.order-status', ['status' => $order->status])
                </div>
                <ul class="mini-list">
                    @foreach ($order->items as $item)
                        <li>
                            <a href="{{ route('books.show', $item->slug) }}" target="_blank" class="cell-book">
                                <img src="{{ $item->image }}" alt="">
                                <span><strong>{{ $item->name }}</strong><span class="muted">{{ $item->meta }}</span></span>
                            </a>
                            <span class="muted" style="white-space: nowrap;">{{ $item->qty }} × ₹{{ number_format($item->price) }}</span>
                            <strong>₹{{ number_format($item->line_total) }}</strong>
                        </li>
                    @endforeach
                </ul>
                <dl class="totals">
                    <div><dt>Subtotal</dt><dd>₹{{ number_format($order->subtotal) }}</dd></div>
                    @if ($order->discount)
                        <div><dt>Coupon {{ $order->coupon_code }}</dt><dd class="up">−₹{{ number_format($order->discount) }}</dd></div>
                    @endif
                    <div><dt>Shipping</dt><dd>{{ $order->shipping ? '₹'.number_format($order->shipping) : 'Free' }}</dd></div>
                    <div class="total"><dt>Total</dt><dd>₹{{ number_format($order->total) }}</dd></div>
                </dl>
            </section>

            <div class="grid grid-2-even">
                <section class="card card-pad">
                    <h2 style="font-family: var(--display); font-size: 1.35rem; margin-bottom: 16px;">Customer</h2>
                    <dl class="dl">
                        <div><dt>Name</dt><dd><a href="{{ route('admin.customers.show', $order->phone) }}" class="link">{{ $order->name }}</a></dd></div>
                        <div><dt>Phone</dt><dd><a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></dd></div>
                        <div><dt>Email</dt><dd><a href="mailto:{{ $order->email }}">{{ $order->email }}</a></dd></div>
                    </dl>
                </section>
                <section class="card card-pad">
                    <h2 style="font-family: var(--display); font-size: 1.35rem; margin-bottom: 16px;">Deliver to</h2>
                    <p>{{ $order->address_line1 }}@if ($order->address_line2)<br>{{ $order->address_line2 }}@endif<br>{{ $order->city }}, {{ $order->state }} {{ $order->pincode }}</p>
                    @if ($order->notes)<p class="muted" style="margin-top: 12px;">Note: {{ $order->notes }}</p>@endif
                </section>
            </div>
        </div>

        <div class="sticky-side">
            <form method="post" action="{{ route('admin.orders.update', $order) }}" class="card card-pad form">
                @csrf @method('patch')
                <h2 style="font-family: var(--display); font-size: 1.35rem;">Update <em style="color: var(--brand);">order</em></h2>
                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="select">
                        @foreach (\App\Models\Order::STATUS_LABELS as $key => $label)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <small>Packed, shipped, delivered and cancelled send the customer an email. Cancelling puts the stock back.</small>
                </div>
                <div class="field">
                    <label for="payment_status">Payment</label>
                    <select id="payment_status" name="payment_status" class="select">
                        @foreach (['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'refunded' => 'Refunded'] as $key => $label)
                            <option value="{{ $key }}" @selected($order->payment_status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <small>{{ $order->payment_method === 'cod' ? 'Cash on delivery: marked paid automatically when delivered.' : 'Paid online'.($order->razorpay_payment_id ? ' · '.$order->razorpay_payment_id : '') }}</small>
                </div>
                <div class="field">
                    <label for="tracking_number">Courier tracking number</label>
                    <input id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="input" placeholder="Shared with the customer when shipped">
                </div>
                <div class="field">
                    <label for="admin_notes">Private notes</label>
                    <textarea id="admin_notes" name="admin_notes" class="textarea" style="min-height: 80px;" placeholder="Only visible to admins">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                </div>
                <button type="submit" class="btn"><x-admin.icon name="check" size="15" /> Save</button>
            </form>

            <section class="card card-pad">
                <dl class="dl">
                    <div><dt>Placed</dt><dd>{{ $order->created_at->format('d M Y, g:i a') }}</dd></div>
                    @if ($order->shipped_at)<div><dt>Shipped</dt><dd>{{ $order->shipped_at->format('d M Y') }}</dd></div>@endif
                    @if ($order->delivered_at)<div><dt>Delivered</dt><dd>{{ $order->delivered_at->format('d M Y') }}</dd></div>@endif
                    <div><dt>Payment</dt><dd>{{ $order->payment_method === 'cod' ? 'Cash on delivery' : 'Online (Razorpay)' }}</dd></div>
                </dl>
            </section>
        </div>
    </div>
@endsection
