@php($site = config('site'))

<footer class="site-footer">
    <div class="wrap">
        <div class="footer-top">
            <div class="footer-contact">
                <img src="{{ asset('images/brand/shubhanga-logo.webp') }}" alt="{{ $site['full_name'] }}" class="footer-logo" width="220" height="109" loading="lazy">
                <p class="footer-tagline">Education First, Business Next.</p>
                @include('partials.social', ['class' => 'social--footer'])
            </div>

            <div>
                <h3>Shop</h3>
                <ul>
                    <li><a href="{{ route('books.index', ['section' => 'Pre-primary']) }}">Pre-primary</a></li>
                    <li><a href="{{ route('books.index', ['section' => 'Primary']) }}">Primary school</a></li>
                    <li><a href="{{ route('books.index', ['board' => 'CBSE']) }}">CBSE books</a></li>
                    <li><a href="{{ route('books.index', ['board' => 'Karnataka']) }}">Karnataka books</a></li>
                    <li><a href="{{ route('books.index', ['board' => 'Andhra Pradesh']) }}">Andhra Pradesh books</a></li>
                    <li><a href="{{ route('books.index', ['board' => 'Telangana']) }}">Telangana books</a></li>
                    <li><a href="{{ route('books.index', ['board' => 'Tamil Nadu']) }}">Tamil Nadu books</a></li>
                </ul>
            </div>
            <div>
                <h3>For schools</h3>
                <ul>
                    <li><a href="{{ route('forms.show', 'specimen-copy') }}">Request a specimen copy</a></li>
                    <li><a href="{{ route('forms.show', 'bulk-order') }}">Bulk order form</a></li>
                    <li><a href="{{ route('resources') }}">Download catalogue</a></li>
                    <li><a href="{{ route('forms.show', 'become-a-distributor') }}">Become a distributor</a></li>
                    <li><a href="{{ route('bookshops') }}">Regional order desks</a></li>
                    <li><a href="{{ route('forms.show', 'write-for-us') }}">Write for us</a></li>
                </ul>
            </div>
            <div>
                <h3>Company</h3>
                <ul>
                    <li><a href="{{ route('about') }}">About us</a></li>
                    <li><a href="{{ route('blog.index') }}">News and media</a></li>
                    <li><a href="{{ route('forms.show', 'contact') }}">Contact us</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h3>Policies</h3>
                <ul>
                    <li><a href="{{ route('policy', 'shipping') }}">Shipping</a></li>
                    <li><a href="{{ route('policy', 'returns') }}">Returns and refunds</a></li>
                    <li><a href="{{ route('policy', 'cancellation') }}">Cancellation</a></li>
                    <li><a href="{{ route('policy', 'payment') }}">Payment options</a></li>
                    <li><a href="{{ route('policy', 'privacy') }}">Privacy</a></li>
                    <li><a href="{{ route('policy', 'terms') }}">Terms of use</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-mid">
            <div class="footer-address">
                <span class="footer-label">Visit us</span>
                <p>{{ $site['contact']['address'] }}</p>
            </div>
            @foreach ($site['contact']['phones'] as $phone)
                <div class="phone"><span>{{ $phone['label'] }}</span><strong>{{ $phone['number'] }}</strong></div>
            @endforeach
        </div>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} {{ $site['full_name'] }}. All rights reserved.</span>
            <span class="footer-credit">Designed and developed by <a href="https://ottomern.com" target="_blank" rel="noopener">Ottomern Technologies</a></span>
            <ul>
                @foreach ($site['social'] as $name => $link)
                    <li><a href="{{ $link }}" target="_blank" rel="noopener">{{ $name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="footer-word" aria-hidden="true" data-footer-word>{{ $site['name'] }}</div>
</footer>
