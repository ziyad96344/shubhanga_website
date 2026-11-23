@extends('layouts.app', ['title' => 'Checkout | '.config('site.full_name'), 'noindex' => true])

@php($method = old('payment_method', $onlineEnabled ? 'online' : 'cod'))

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Cart' => route('cart.show'), 'Checkout' => null],
        'eyebrow' => 'Almost done',
        'title' => '<em>Checkout</em>',
    ])

    <form method="post" action="{{ route('checkout.store') }}" class="wrap checkout" novalidate data-checkout
          data-cod-shipping="{{ $codShipping }}" data-online-shipping="{{ $onlineShipping }}">
        @csrf
        <div class="checkout-main">
            @if (session('payment_error'))
                <div class="form-alert" role="alert">{{ session('payment_error') }}</div>
            @elseif ($errors->any())
                <div class="form-alert" role="alert">Please check the highlighted fields and try again.</div>
            @endif

            <fieldset class="checkout-step">
                <legend><span>1</span> Contact</legend>
                <div class="form-grid">
                    @foreach ([['name', 'Full name', 'text', 'autocomplete="name"'], ['phone', 'Mobile number', 'tel', 'autocomplete="tel-national" inputmode="numeric" maxlength="10"'], ['email', 'Email', 'email', 'autocomplete="email"']] as [$name, $label, $type, $attrs])
                        <div class="field {{ $name === 'email' ? 'field--wide' : '' }} {{ $errors->has($name) ? 'has-error' : '' }}">
                            <label for="c-{{ $name }}">{{ $label }}<span aria-hidden="true"> *</span></label>
                            <input id="c-{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name) }}" class="field-input" required {!! $attrs !!} @error($name) aria-invalid="true" aria-describedby="c-{{ $name }}-error" @enderror>
                            @error($name)<span class="field-error" id="c-{{ $name }}-error">{{ $message }}</span>@enderror
                            @if ($name === 'email' && ! $errors->has('email'))<span class="field-hint">We send the order confirmation here.</span>@endif
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <fieldset class="checkout-step">
                <legend><span>2</span> Delivery address</legend>
                <div class="form-grid">
                    @foreach ([['address_line1', 'House, street, area', true, 'autocomplete="address-line1"'], ['address_line2', 'Landmark (optional)', false, 'autocomplete="address-line2"']] as [$name, $label, $req, $attrs])
                        <div class="field field--wide {{ $errors->has($name) ? 'has-error' : '' }}">
                            <label for="c-{{ $name }}">{{ $label }}@if ($req)<span aria-hidden="true"> *</span>@endif</label>
                            <input id="c-{{ $name }}" type="text" name="{{ $name }}" value="{{ old($name) }}" class="field-input" @if ($req) required @endif {!! $attrs !!}>
                            @error($name)<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    @endforeach
                    <div class="field {{ $errors->has('city') ? 'has-error' : '' }}">
                        <label for="c-city">City<span aria-hidden="true"> *</span></label>
                        <input id="c-city" type="text" name="city" value="{{ old('city') }}" class="field-input" required autocomplete="address-level2">
                        @error('city')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field {{ $errors->has('pincode') ? 'has-error' : '' }}">
                        <label for="c-pincode">PIN code<span aria-hidden="true"> *</span></label>
                        <input id="c-pincode" type="text" name="pincode" value="{{ old('pincode') }}" class="field-input" required inputmode="numeric" maxlength="6" autocomplete="postal-code">
                        @error('pincode')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field field--wide {{ $errors->has('state') ? 'has-error' : '' }}">
                        <label for="c-state">State<span aria-hidden="true"> *</span></label>
                        <select id="c-state" name="state" class="select" required autocomplete="address-level1">
                            <option value="" disabled @selected(! old('state'))>Choose your state</option>
                            @foreach ($states as $state)
                                <option @selected(old('state') === $state)>{{ $state }}</option>
                            @endforeach
                        </select>
                        @error('state')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field field--wide">
                        <label for="c-notes">Delivery notes (optional)</label>
                        <textarea id="c-notes" name="notes" rows="2" class="field-input">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset class="checkout-step">
                <legend><span>3</span> Payment</legend>
                <div class="pay-options">
                    @if ($onlineEnabled)
                        <label class="pay-option">
                            <input type="radio" name="payment_method" value="online" @checked($method === 'online')>
                            <span class="pay-body">
                                <strong>Pay online</strong>
                                <small>UPI, cards and net banking through Razorpay. {{ $onlineShipping ? '' : 'Free shipping.' }}</small>
                            </span>
                        </label>
                    @endif
                    <label class="pay-option">
                        <input type="radio" name="payment_method" value="cod" @checked($method === 'cod' || ! $onlineEnabled)>
                        <span class="pay-body">
                            <strong>Cash on delivery</strong>
                            <small>Pay when the books arrive. ₹{{ $codShipping }} delivery charge.</small>
                        </span>
                    </label>
                </div>
                @unless ($onlineEnabled)
                    <p class="field-hint" style="margin-top: 12px">Online payment will appear here once Razorpay keys are added.</p>
                @endunless
                @error('payment_method')<span class="field-error">{{ $message }}</span>@enderror
            </fieldset>
        </div>

        <div class="checkout-side">
            @include('checkout.partials.summary', [
                'cart' => $cart,
                'shipping' => $method === 'online' && $onlineEnabled ? $onlineShipping : $codShipping,
                'showItems' => true,
                'checkout' => true,
            ])
            <button type="submit" class="btn checkout-submit">Place order
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
            <p class="form-note">By placing the order you agree to our <a href="{{ route('policy', 'terms') }}">terms</a> and <a href="{{ route('policy', 'returns') }}">returns policy</a>.</p>
        </div>
    </form>
@endsection
