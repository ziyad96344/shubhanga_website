@extends('admin.layouts.app')

@section('title', 'Sales report')
@section('crumb', 'Reports')
@section('heading')Sales <em>report</em>@endsection

@section('content')
    <form method="get" class="card card-pad toolbar">
        <div class="filters">
            <label class="muted">From <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="input" style="min-width: 0;"></label>
            <label class="muted">To <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="input" style="min-width: 0;"></label>
            <button type="submit" class="btn btn--sm">Show</button>
        </div>
        <div class="tabs">
            @foreach ([7 => 'Last 7 days', 30 => 'Last 30 days', 90 => 'Last 90 days'] as $days => $label)
                <a href="{{ route('admin.reports.sales', ['from' => now()->subDays($days - 1)->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}" class="tab">{{ $label }}</a>
            @endforeach
        </div>
    </form>

    <div class="stats">
        <div class="stat stat--brand"><p class="stat-label">Revenue</p><p class="stat-value">₹{{ number_format($summary['revenue']) }}</p><p class="stat-note">{{ $from->format('d M') }} to {{ $to->format('d M Y') }}</p></div>
        <div class="stat"><p class="stat-label">Orders</p><p class="stat-value">{{ number_format($summary['orders']) }}</p><p class="stat-note">{{ $summary['cod'] }} COD · {{ $summary['online'] }} online</p></div>
        <div class="stat"><p class="stat-label">Average order</p><p class="stat-value">₹{{ number_format($summary['avg']) }}</p></div>
        <div class="stat"><p class="stat-label">Books sold</p><p class="stat-value">{{ number_format($summary['books_sold']) }}</p></div>
        <div class="stat"><p class="stat-label">Coupon discounts</p><p class="stat-value">₹{{ number_format($summary['discount']) }}</p></div>
    </div>

    <div class="grid grid-2">
        <section class="card">
            <div class="card-head"><h2>Day by <em>day</em></h2></div>
            @if ($daily->isEmpty())
                <div class="empty">No sales in this period.</div>
            @else
                @php($max = max(1, $daily->max('revenue')))
                <div class="chart">
                    @foreach ($daily as $day)
                        <div class="chart-bar" style="--h: {{ max(2, round($day->revenue / $max * 100)) }}%">
                            <span>{{ \Carbon\Carbon::parse($day->day)->format('d M') }}: ₹{{ number_format($day->revenue) }}, {{ $day->orders }} orders</span>
                        </div>
                    @endforeach
                </div>
                <div class="chart-axis"><span>{{ \Carbon\Carbon::parse($daily->first()->day)->format('d M') }}</span><span>{{ \Carbon\Carbon::parse($daily->last()->day)->format('d M') }}</span></div>
            @endif
        </section>

        <section class="card">
            <div class="card-head"><h2>Best <em>sellers</em></h2></div>
            @if ($topBooks->isEmpty())
                <div class="empty">No books sold in this period.</div>
            @else
                <ul class="mini-list">
                    @foreach ($topBooks as $row)
                        <li><span><strong>{{ $row->name }}</strong><div class="muted">{{ $row->meta }}</div></span><span style="text-align: right;"><strong>{{ $row->qty }}</strong><div class="muted">₹{{ number_format($row->revenue) }}</div></span></li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>

    <section class="card">
        <div class="card-head"><h2>Orders in this <em>period</em></h2></div>
        @if ($orders->isEmpty())
            <div class="empty">No orders.</div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Order</th><th>Customer</th><th>Payment</th><th class="num">Discount</th><th class="num">Total</th></tr></thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="link">{{ $order->number }}</a><div class="muted">{{ $order->created_at->format('d M Y') }}</div></td>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->payment_method === 'cod' ? 'COD' : 'Online' }}</td>
                                <td class="num">{{ $order->discount ? '₹'.number_format($order->discount) : '—' }}</td>
                                <td class="num strong">₹{{ number_format($order->total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('admin.partials.pager', ['paginator' => $orders])
    </section>
@endsection
