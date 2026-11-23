@extends('layouts.app', ['title' => 'Order from your region | '.config('site.full_name'), 'description' => 'Regional order desks for Shubhanga books in Karnataka, Andhra Pradesh, Telangana, CBSE schools and more.'])

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Order from your region' => null],
        'eyebrow' => 'Regional order desks',
        'title' => 'Order from <em>your region</em>',
        'intro' => 'Our books reach schools through partnered schools, trusted dealers and distributors. Contact the desk for your region, or write to '.config('site.contact.order_email').'.',
    ])

    <section class="wrap shop-page" data-shop-finder>
        <div class="shop-filters">
            <label class="shop-search">
                <span>Search a region</span>
                <input type="search" class="field-input" placeholder="For example, Telangana" data-shop-search>
            </label>
            <label>
                <span>Region</span>
                <select class="select" data-shop-state>
                    <option value="">All regions</option>
                    @foreach ($states as $state)
                        <option>{{ $state }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <p class="shop-count" aria-live="polite"><strong data-shop-count>{{ $shops->count() }}</strong> order desks</p>

        <ul class="shop-grid">
            @foreach ($shops as $shop)
                <li class="shop-card" data-shop data-state="{{ $shop['state'] }}" data-text="{{ strtolower($shop['name'].' '.$shop['city'].' '.$shop['state']) }}">
                    <span class="shop-city">{{ $shop['city'] }}, {{ $shop['state'] }}</span>
                    <h2>{{ $shop['name'] }}</h2>
                    <p>{{ $shop['address'] }}</p>
                    <div class="shop-actions">
                        <a href="tel:{{ preg_replace('/\s+/', '', $shop['phone']) }}" class="link-arrow">{{ $shop['phone'] }}</a>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode($shop['name'].', '.$shop['address'].', '.$shop['city']) }}" target="_blank" rel="noopener" class="link-arrow">Map</a>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="empty-state" data-shop-empty hidden>
            <h2>No order desk found</h2>
            <p>Write to us and we will connect you with the right team.</p>
            <a href="{{ route('forms.show', 'contact') }}" class="btn">Contact us</a>
        </div>
    </section>
@endsection
