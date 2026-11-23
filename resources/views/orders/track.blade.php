@extends('layouts.app', ['title' => 'Track your order | '.config('site.full_name'), 'noindex' => true])

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Track order' => null],
        'eyebrow' => 'Your orders',
        'title' => 'Track your <em>order</em>',
        'intro' => 'Enter the order number from your confirmation email and the mobile number you used at checkout.',
    ])

    <section class="wrap narrow-page">
        <div class="form-card">
            @if (session('not_found'))
                <div class="form-alert" role="alert">We could not find an order with those details. Check the order number and mobile number.</div>
            @endif
            <form method="post" action="{{ route('orders.track.submit') }}" class="form-grid" novalidate>
                @csrf
                <div class="field {{ $errors->has('number') ? 'has-error' : '' }}">
                    <label for="t-number">Order number</label>
                    <input id="t-number" name="number" value="{{ old('number') }}" class="field-input" placeholder="SP-26-XXXXXX" required>
                    @error('number')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field {{ $errors->has('phone') ? 'has-error' : '' }}">
                    <label for="t-phone">Mobile number</label>
                    <input id="t-phone" name="phone" type="tel" value="{{ old('phone') }}" class="field-input" inputmode="numeric" required>
                    @error('phone')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field field--wide form-actions">
                    <button type="submit" class="btn">Track order</button>
                </div>
            </form>
        </div>
    </section>
@endsection
