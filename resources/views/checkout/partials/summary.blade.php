{{-- Order summary card. Expects $cart, $shipping, $showItems; optional $checkout (button hidden). --}}
@php($left = $cart['free_from'] - $cart['subtotal'])
<aside class="summary-card" data-summary data-subtotal="{{ $cart['subtotal'] - ($cart['discount'] ?? 0) }}">
    <h2>Order summary</h2>

    @if ($showItems)
        <ul class="summary-items">
            @foreach ($cart['items'] as $item)
                <li>
                    <span class="summary-thumb"><img src="{{ $item['image'] }}" alt=""><b>{{ $item['qty'] }}</b></span>
                    <span class="summary-name">{{ $item['name'] }}<small>{{ $item['meta'] }}</small></span>
                    <span>₹{{ number_format($item['line_total']) }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    @php($discount = $cart['discount'] ?? 0)
    <dl class="summary-rows">
        <div><dt>Subtotal</dt><dd>₹{{ number_format($cart['subtotal']) }}</dd></div>
        @if ($discount)
            <div class="summary-discount"><dt>Coupon {{ $cart['coupon']['code'] }}</dt><dd>−₹{{ number_format($discount) }}</dd></div>
        @endif
        <div><dt>Shipping</dt><dd data-summary-shipping>{{ $shipping ? '₹'.$shipping : 'Free' }}</dd></div>
        <div class="summary-total"><dt>Total</dt><dd data-summary-total>₹{{ number_format($cart['subtotal'] - $discount + $shipping) }}</dd></div>
    </dl>

    @isset($checkout)
        {{-- Coupon: lives inside the checkout form, so it submits with formaction --}}
        <div class="coupon-box">
            @if ($cart['coupon'] ?? null)
                <p class="coupon-applied">
                    <span><strong>{{ $cart['coupon']['code'] }}</strong> {{ $cart['coupon']['label'] }}</span>
                    <button type="submit" formaction="{{ route('checkout.coupon.remove') }}" formnovalidate class="coupon-remove">Remove</button>
                </p>
            @else
                <label for="coupon-code" class="sr-only">Coupon code</label>
                <div class="coupon-row">
                    <input id="coupon-code" type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="Coupon code" autocomplete="off" class="field-input">
                    <button type="submit" formaction="{{ route('checkout.coupon') }}" formnovalidate class="btn btn--ghost coupon-apply">Apply</button>
                </div>
                @error('coupon_code')<span class="field-error">{{ $message }}</span>@enderror
            @endif
            @if (session('coupon_applied'))<p class="coupon-ok" role="status">{{ session('coupon_applied') }}</p>@endif
        </div>
    @endisset

    @if ($left > 0)
        <p class="summary-note">Add ₹{{ number_format($left) }} more for free shipping on prepaid orders.</p>
    @else
        <p class="summary-note">Free shipping on prepaid orders. Cash on delivery adds ₹{{ config('site.shipping.fee') }}.</p>
    @endif

    @empty($checkout)
        <a href="{{ route('checkout.show') }}" class="btn summary-btn">Proceed to checkout
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
    @endempty

    <ul class="summary-trust">
        <li>Secure payment</li>
        <li>Dispatched in 2 working days</li>
        <li>Easy 7-day returns</li>
    </ul>
</aside>
