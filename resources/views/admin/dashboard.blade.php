@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('crumb', now()->format('l, j F Y'))
@section('heading')
    Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, <em>{{ Str::before(auth()->user()->name, ' ') }}</em>
@endsection
@section('actions')
    <a href="{{ route('admin.books.create') }}" class="btn btn--sm"><x-admin.icon name="plus" size="14" /> Add book</a>
@endsection

@section('content')
    @php($maxRevenue = max(1, $chart->max('revenue')))

    <div class="stats">
        <div class="stat stat--brand">
            <span class="stat-icon"><x-admin.icon name="rupee" size="20" /></span>
            <p class="stat-label">Revenue this month</p>
            <p class="stat-value">₹{{ number_format($stats['this_month']) }}</p>
            <p class="stat-note">{{ $stats['growth'] >= 0 ? '▲' : '▼' }} {{ abs($stats['growth']) }}% on last month</p>
        </div>
        <div class="stat">
            <span class="stat-icon"><x-admin.icon name="orders" size="20" /></span>
            <p class="stat-label">Orders</p>
            <p class="stat-value">{{ number_format($stats['orders']) }}</p>
            <p class="stat-note"><span class="{{ $stats['new_orders'] ? 'down' : '' }}">{{ $stats['new_orders'] }} new</span> · {{ $stats['today_orders'] }} today</p>
        </div>
        <div class="stat">
            <span class="stat-icon"><x-admin.icon name="books" size="20" /></span>
            <p class="stat-label">Books live</p>
            <p class="stat-value">{{ number_format($stats['active_books']) }}</p>
            <p class="stat-note">of {{ $stats['books'] }} · <span class="{{ $stats['low_stock'] ? 'down' : '' }}">{{ $stats['low_stock'] }} low on stock</span></p>
        </div>
        <div class="stat">
            <span class="stat-icon"><x-admin.icon name="customers" size="20" /></span>
            <p class="stat-label">Customers</p>
            <p class="stat-value">{{ number_format($stats['customers']) }}</p>
            <p class="stat-note">Average order ₹{{ number_format($stats['avg_order']) }}</p>
        </div>
        <div class="stat">
            <span class="stat-icon"><x-admin.icon name="enquiries" size="20" /></span>
            <p class="stat-label">New enquiries</p>
            <p class="stat-value">{{ $stats['new_enquiries'] }}</p>
            <p class="stat-note"><a href="{{ route('admin.enquiries.index', ['status' => 'new']) }}" class="link">Open messages</a></p>
        </div>
    </div>

    <div class="grid grid-2">
        <section class="card">
            <div class="card-head">
                <h2>Revenue, <em>last 30 days</em></h2>
                <span class="muted">₹{{ number_format($chart->sum('revenue')) }} · {{ $chart->sum('orders') }} orders</span>
            </div>
            <div class="chart">
                @foreach ($chart as $day)
                    <div class="chart-bar {{ $day['revenue'] ? '' : 'is-zero' }}" style="--h: {{ max(2, round($day['revenue'] / $maxRevenue * 100)) }}%">
                        <span>{{ $day['label'] }}: ₹{{ number_format($day['revenue']) }}, {{ $day['orders'] }} {{ Str::plural('order', $day['orders']) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="chart-axis"><span>{{ $chart->first()['label'] }}</span><span>{{ $chart->get(15)['label'] }}</span><span>Today</span></div>
        </section>

        <section class="card">
            <div class="card-head"><h2>Orders by <em>status</em></h2></div>
            @php($totalOrders = max(1, $byStatus->sum()))
            <div class="status-bars">
                @foreach (\App\Models\Order::STATUS_LABELS as $key => $label)
                    <div class="status-bar">
                        <span>{{ $label }}</span>
                        <i><b style="--w: {{ round(($byStatus[$key] ?? 0) / $totalOrders * 100) }}%"></b></i>
                        <strong style="text-align: right;">{{ $byStatus[$key] ?? 0 }}</strong>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="grid grid-2">
        <section class="card">
            <div class="card-head">
                <h2>Recent <em>orders</em></h2>
                <a href="{{ route('admin.orders.index') }}" class="link">All orders</a>
            </div>
            @if ($recentOrders->isEmpty())
                <div class="empty"><h3>No orders yet</h3><p>Orders placed on the website show up here.</p></div>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>Order</th><th>Customer</th><th>Status</th><th class="num">Total</th></tr></thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}" class="link">{{ $order->number }}</a><div class="muted">{{ $order->created_at->diffForHumans() }}</div></td>
                                    <td>{{ $order->name }}<div class="muted">{{ $order->city }}</div></td>
                                    <td>@include('admin.partials.order-status', ['status' => $order->status])</td>
                                    <td class="num strong">₹{{ number_format($order->total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <div class="grid">
            <section class="card">
                <div class="card-head">
                    <h2>Low <em>stock</em></h2>
                    <a href="{{ route('admin.books.index', ['status' => 'low']) }}" class="link">See all</a>
                </div>
                @if ($lowStock->isEmpty())
                    <div class="empty" style="padding: 28px;">Every book has more than 10 copies.</div>
                @else
                    <ul class="mini-list">
                        @foreach ($lowStock as $book)
                            <li>
                                <a href="{{ route('admin.books.edit', $book) }}" class="cell-book">
                                    <img src="{{ $book->image }}" alt="">
                                    <span><strong>{{ $book->displayName() }}</strong><span class="muted">{{ \App\Support\Catalog::classLabel($book->class) }}</span></span>
                                </a>
                                <span class="badge {{ $book->stock ? 'badge--warn' : 'badge--brand' }}">{{ $book->stock }} left</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="card">
                <div class="card-head">
                    <h2>Latest <em>enquiries</em></h2>
                    <a href="{{ route('admin.enquiries.index') }}" class="link">All</a>
                </div>
                @if ($recentEnquiries->isEmpty())
                    <div class="empty" style="padding: 28px;">No messages yet.</div>
                @else
                    <ul class="mini-list">
                        @foreach ($recentEnquiries as $enquiry)
                            <li>
                                <a href="{{ route('admin.enquiries.show', $enquiry) }}">
                                    <strong>{{ $enquiry->name }}</strong>
                                    <div class="muted">{{ Str::headline($enquiry->type) }} · {{ $enquiry->created_at->diffForHumans() }}</div>
                                </a>
                                @if ($enquiry->status === 'new')<span class="badge badge--brand">New</span>@endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
@endsection
