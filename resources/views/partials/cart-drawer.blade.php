<div class="drawer-backdrop" data-cart-close data-cart-backdrop></div>

<aside class="drawer" role="dialog" aria-modal="true" aria-labelledby="cart-title" data-cart-drawer data-endpoint="{{ url('/cart/items') }}" aria-hidden="true">
    <div class="drawer-head">
        <h2 id="cart-title">Your cart</h2>
        <button type="button" class="icon-btn" aria-label="Close cart" data-cart-close>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </button>
    </div>
    <div class="drawer-body" data-cart-items></div>
    <div class="drawer-foot" data-cart-foot hidden>
        <div>
            <small data-ship-text></small>
            <div class="ship-bar" style="margin-top: 8px"><span data-ship-bar></span></div>
        </div>
        <div class="total"><span>Subtotal</span><span data-cart-total>₹0</span></div>
        <a href="{{ route('checkout.show') }}" class="btn">Go to checkout
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a href="{{ route('cart.show') }}" class="link-arrow drawer-cart-link">View full cart</a>
    </div>
</aside>
