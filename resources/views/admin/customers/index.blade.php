@extends('admin.layouts.app')

@section('title', 'Customers')
@section('crumb', 'People')
@section('heading')Customers@endsection

@section('content')
    <form method="get" class="card card-pad toolbar">
        <div class="filters">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, email or phone" class="input">
            <button type="submit" class="btn btn--ghost btn--sm"><x-admin.icon name="search" size="14" /> Search</button>
            @if (request('search'))<a href="{{ route('admin.customers.index') }}" class="link">Clear</a>@endif
        </div>
        <p class="muted">Everyone who has ordered, grouped by phone number.</p>
    </form>

    <section class="card">
        @if ($customers->isEmpty())
            <div class="empty"><h3>No customers yet</h3><p>Customers appear here after their first order.</p></div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Customer</th><th>Location</th><th class="num">Orders</th><th class="num">Spent</th><th>Last order</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td><a href="{{ route('admin.customers.show', $customer->phone) }}" class="strong">{{ $customer->name }}</a><div class="muted">{{ $customer->phone }} · {{ $customer->email }}</div></td>
                                <td>{{ $customer->city }}<div class="muted">{{ $customer->state }}</div></td>
                                <td class="num">{{ $customer->orders }}</td>
                                <td class="num strong">₹{{ number_format($customer->spent) }}</td>
                                <td>{{ \Carbon\Carbon::parse($customer->last_order)->format('d M Y') }}</td>
                                <td><div class="row-actions"><a href="{{ route('admin.customers.show', $customer->phone) }}" class="icon-btn" title="Open"><x-admin.icon name="arrow-right" size="15" /></a></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('admin.partials.pager', ['paginator' => $customers])
    </section>
@endsection
