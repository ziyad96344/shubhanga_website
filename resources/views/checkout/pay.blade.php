@extends('layouts.app', ['title' => 'Payment | '.config('site.full_name'), 'noindex' => true])

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Checkout' => route('checkout.show'), 'Payment' => null],
        'eyebrow' => 'Order '.$order->number,
        'title' => 'Complete <em>payment</em>',
        'intro' => 'Your order is saved. Pay ₹'.number_format($order->total).' securely to confirm it.',
    ])

    <section class="wrap narrow-page">
        @if (session('payment_error'))
            <div class="form-alert" role="alert">{{ session('payment_error') }}</div>
        @endif

        <div class="help-card">
            <div>
                <h2>₹{{ number_format($order->total) }}</h2>
                <p>{{ $order->items->sum('qty') ?? '' }} books, delivered to {{ $order->city }}</p>
            </div>
            <button type="button" class="btn" data-razorpay-pay>Pay now</button>
        </div>

        <form method="post" action="{{ route('checkout.verify', $order->number) }}" data-razorpay-form hidden>
            @csrf
            <input type="hidden" name="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id">
            <input type="hidden" name="razorpay_signature">
        </form>
    </section>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        (function () {
            const form = document.querySelector('[data-razorpay-form]');
            const rzp = new Razorpay({
                key: @json($key),
                amount: {{ $order->total * 100 }},
                currency: 'INR',
                name: @json(config('site.full_name')),
                description: 'Order ' + @json($order->number),
                order_id: @json($order->razorpay_order_id),
                prefill: { name: @json($order->name), email: @json($order->email), contact: @json($order->phone) },
                theme: { color: '#c8102e' },
                handler: function (res) {
                    form.razorpay_payment_id.value = res.razorpay_payment_id;
                    form.razorpay_order_id.value = res.razorpay_order_id;
                    form.razorpay_signature.value = res.razorpay_signature;
                    form.submit();
                },
            });
            document.querySelector('[data-razorpay-pay]').addEventListener('click', function () { rzp.open(); });
            rzp.open();
        })();
    </script>
@endsection
