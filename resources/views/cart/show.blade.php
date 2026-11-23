@extends('layouts.app', ['title' => 'Your cart | '.config('site.full_name'), 'noindex' => true])

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Cart' => null],
        'eyebrow' => $cart['count'].' '.Str::plural('book', $cart['count']),
        'title' => 'Your <em>cart</em>',
    ])

    <section class="wrap cart-page">
        @if (! $cart['items'])
            <div class="empty-state">
                <h2>Your cart is empty</h2>
                <p>Browse by class or board and add the books you need.</p>
                <a href="{{ route('books.index') }}" class="btn">Browse books</a>
            </div>
        @else
            <div class="cart-lines">
                @foreach ($cart['items'] as $item)
                    <div class="cart-line">
                        <a href="{{ $item['url'] }}" class="cart-line-cover"><x-book-cover :book="['image' => $item['image'], 'series' => $item['name'], 'subject' => '', 'class' => '']" /></a>
                        <div class="cart-line-info">
                            <h2><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></h2>
                            <p>{{ $item['meta'] }}</p>
                            <p class="cart-line-unit">₹{{ number_format($item['price']) }} each</p>
                        </div>
                        <div class="cart-line-qty">
                            <form method="post" action="{{ route('cart.update', $item['id']) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="qty" value="{{ $item['qty'] - 1 }}">
                                <button type="submit" aria-label="Remove one">−</button>
                            </form>
                            <span>{{ $item['qty'] }}</span>
                            <form method="post" action="{{ route('cart.update', $item['id']) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
                                <button type="submit" aria-label="Add one">+</button>
                            </form>
                        </div>
                        <div class="cart-line-total">
                            <strong>₹{{ number_format($item['line_total']) }}</strong>
                            <form method="post" action="{{ route('cart.remove', $item['id']) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="cart-remove">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
                <a href="{{ route('books.index') }}" class="link-arrow">Continue shopping</a>
            </div>

            @include('checkout.partials.summary', ['cart' => $cart, 'shipping' => $cart['shipping'], 'showItems' => false])
        @endif
    </section>
@endsection
