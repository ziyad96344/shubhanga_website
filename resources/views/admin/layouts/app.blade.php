<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') | {{ config('site.name') }} Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/favicon-192.png') }}">
    @fonts
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body>
@php
    $newOrders = \App\Models\Order::where('status', 'placed')->count();
    $newEnquiries = \App\Models\Enquiry::where('status', 'new')->count();
    $nav = [
        ['admin.dashboard', 'dashboard', 'Dashboard', null, 'admin.dashboard'],
        ['admin.orders.index', 'orders', 'Orders', $newOrders, 'admin.orders.*'],
        ['admin.books.index', 'books', 'Books', null, 'admin.books.*'],
        ['admin.customers.index', 'customers', 'Customers', null, 'admin.customers.*'],
        ['admin.coupons.index', 'coupons', 'Coupons', null, 'admin.coupons.*'],
        'Messages',
        ['admin.enquiries.index', 'enquiries', 'Enquiries', $newEnquiries, 'admin.enquiries.*'],
        'Store',
        ['admin.reports.sales', 'reports', 'Sales report', null, 'admin.reports.*'],
        ['admin.settings', 'settings', 'Settings', null, 'admin.settings*'],
    ];
    $user = auth()->user();
@endphp
<div class="a-shell">
    <aside class="a-side" data-side>
        <a href="{{ route('admin.dashboard') }}" class="a-brand">
            <img src="{{ asset('images/brand/shubhanga-icon.png') }}" alt="">
            <span><strong>{{ config('site.name') }}</strong><span>Admin panel</span></span>
        </a>
        <nav class="a-nav" aria-label="Admin">
            @foreach ($nav as $item)
                @if (is_string($item))
                    <p class="a-nav-label">{{ $item }}</p>
                @else
                    @php([$route, $icon, $label, $count, $pattern] = $item)
                    <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'is-active' : '' }}">
                        <x-admin.icon :name="$icon" />
                        <span>{{ $label }}</span>
                        @if ($count)<span class="a-count">{{ $count }}</span>@endif
                    </a>
                @endif
            @endforeach
        </nav>
        <div class="a-side-foot">
            <a href="{{ route('home') }}" target="_blank" rel="noopener"><x-admin.icon name="external" size="15" /> View website</a>
            <form method="post" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,.7);"><x-admin.icon name="logout" size="15" /> Sign out</button>
            </form>
        </div>
    </aside>
    <div class="a-backdrop" data-backdrop></div>

    <div class="a-main">
        <header class="a-top">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" class="icon-btn a-menu" aria-label="Open menu" data-menu><x-admin.icon name="menu" /></button>
                <div>
                    @hasSection('crumb')<p class="a-crumb">@yield('crumb')</p>@endif
                    <h1>@yield('heading', 'Dashboard')</h1>
                </div>
            </div>
            <div class="a-top-right">
                @yield('actions')
                <div class="a-user">
                    <span>{{ $user?->name }}</span>
                    <span class="a-avatar" style="display: grid;">{{ strtoupper(mb_substr($user?->name ?? 'A', 0, 1)) }}</span>
                </div>
            </div>
        </header>

        <main class="a-content">
            @if (session('success'))
                <div class="flash flash--ok" role="status" data-flash><x-admin.icon name="check" /> {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="flash flash--err" role="alert"><x-admin.icon name="alert" /> {{ session('error') }}</div>
            @endif
            @if ($errors->any() && ! View::hasSection('inline_errors'))
                <div class="flash flash--err" role="alert"><x-admin.icon name="alert" /> Please check the highlighted fields.</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
