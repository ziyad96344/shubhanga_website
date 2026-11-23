{{-- Order summary card. Expects $cart, $shipping, $showItems; optional $checkout (button hidden). --}}
@php($left = $cart['free_from'] - $cart['subtotal'])
<aside class="summary-card" data-summary data-subtotal="{{ $cart['subtotal'] }}">
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

    <dl class="summary-rows">
        <div><dt>Subtotal</dt><dd>₹{{ number_format($cart['subtotal']) }}</dd></div>
        <div><dt>Shipping</dt><dd data-summary-shipping>{{ $shipping ? '₹'.$shipping : 'Free' }}</dd></div>
        <div class="summary-total"><dt>Total</dt><dd data-summary-total>₹{{ number_format($cart['subtotal'] + $shipping) }}</dd></div>
    </dl>

    @if ($left > 0)
        <p class="summary-note">Add ₹{{ number_format($left) }} more for free shipping on prepaid orders.</p>
    @else
        <p class="summary-note">Free shipping on prepaid orders. Cash on delivery adds ₹{{ \App\Support\Cart::SHIPPING_FEE }}.</p>
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
