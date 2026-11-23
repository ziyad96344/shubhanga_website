<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public const STATES = ['Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chandigarh', 'Chhattisgarh', 'Delhi', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Ladakh', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Puducherry', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'];

    public function show()
    {
        $cart = Cart::summary();
        if (! $cart['items']) {
            return redirect()->route('cart.show');
        }

        return view('checkout.show', [
            'cart' => $cart,
            'states' => self::STATES,
            'onlineEnabled' => $this->onlineEnabled(),
            'codShipping' => Cart::shipping($cart['subtotal'], 'cod'),
            'onlineShipping' => Cart::shipping($cart['subtotal'], 'online'),
        ]);
    }

    public function store(Request $request)
    {
        $cart = Cart::summary();
        if (! $cart['items']) {
            return redirect()->route('cart.show');
        }

        $methods = $this->onlineEnabled() ? ['cod', 'online'] : ['cod'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'address_line1' => ['required', 'string', 'max:200'],
            'address_line2' => ['nullable', 'string', 'max:200'],
            'city' => ['required', 'string', 'max:80'],
            'state' => ['required', 'in:'.implode(',', self::STATES)],
            'pincode' => ['required', 'digits:6'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:'.implode(',', $methods)],
        ], [
            'required' => 'Please fill in this field.',
            'email' => 'Enter a valid email address.',
            'phone.regex' => 'Enter a 10-digit Indian mobile number.',
            'pincode.digits' => 'Enter a 6-digit PIN code.',
            'in' => 'Choose one of the options.',
        ]);

        $shipping = Cart::shipping($cart['subtotal'], $data['payment_method']);
        $online = $data['payment_method'] === 'online';

        $order = DB::transaction(function () use ($data, $cart, $shipping, $online, $request) {
            $order = Order::create($data + [
                'number' => Order::newNumber(),
                'subtotal' => $cart['subtotal'],
                'shipping' => $shipping,
                'total' => $cart['subtotal'] + $shipping,
                'status' => $online ? 'awaiting_payment' : 'placed',
                'ip' => $request->ip(),
            ]);

            foreach ($cart['items'] as $item) {
                $order->items()->create([
                    'slug' => $item['id'],
                    'name' => $item['name'],
                    'meta' => $item['meta'],
                    'image' => $item['image'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        $this->remember($order);

        if (! $online) {
            Cart::clear();
            $this->notify($order);

            return redirect()->route('orders.show', $order->number);
        }

        return redirect()->route('checkout.pay', $order->number);
    }

    /** Opens Razorpay Checkout for an order awaiting payment. */
    public function pay(string $number)
    {
        $order = $this->ownOrder($number);
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->number);
        }
        abort_unless($this->onlineEnabled(), 404);

        if (! $order->razorpay_order_id) {
            $response = Http::withBasicAuth(config('services.razorpay.key'), config('services.razorpay.secret'))
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => $order->total * 100, // paise
                    'currency' => 'INR',
                    'receipt' => $order->number,
                ]);

            if ($response->failed()) {
                Log::error('Razorpay order failed', ['order' => $order->number, 'body' => $response->body()]);

                return redirect()->route('checkout.show')->with('payment_error', 'Online payment is not available right now. Please choose cash on delivery or try again.');
            }

            $order->update(['razorpay_order_id' => $response->json('id')]);
        }

        return view('checkout.pay', ['order' => $order, 'key' => config('services.razorpay.key')]);
    }

    /** Razorpay posts back here; the signature proves the payment is genuine. */
    public function verify(Request $request, string $number)
    {
        $order = $this->ownOrder($number);

        $data = $request->validate([
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $expected = hash_hmac('sha256', $order->razorpay_order_id.'|'.$data['razorpay_payment_id'], config('services.razorpay.secret'));

        if ($data['razorpay_order_id'] !== $order->razorpay_order_id || ! hash_equals($expected, $data['razorpay_signature'])) {
            $order->update(['payment_status' => 'failed']);

            return redirect()->route('checkout.pay', $order->number)->with('payment_error', 'We could not confirm that payment. No money has been taken twice; please try again.');
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'placed',
            'razorpay_payment_id' => $data['razorpay_payment_id'],
        ]);
        Cart::clear();
        $this->notify($order);

        return redirect()->route('orders.show', $order->number);
    }

    private function onlineEnabled(): bool
    {
        return filled(config('services.razorpay.key')) && filled(config('services.razorpay.secret'));
    }

    /** Orders placed in this browser session can be viewed without a lookup. */
    private function remember(Order $order): void
    {
        session()->push('my_orders', $order->number);
    }

    private function ownOrder(string $number): Order
    {
        abort_unless(in_array($number, session('my_orders', []), true), 404);

        return Order::where('number', $number)->firstOrFail();
    }

    private function notify(Order $order): void
    {
        $order->load('items');
        $lines = $order->items->map(fn ($i) => "{$i->qty} × {$i->name} ({$i->meta}) = ₹{$i->line_total}")->implode("\n");
        $body = "Order {$order->number}\n\n{$lines}\n\nSubtotal: ₹{$order->subtotal}\nShipping: ₹{$order->shipping}\nTotal: ₹{$order->total}\n"
            ."Payment: ".($order->payment_method === 'cod' ? 'Cash on delivery' : 'Paid online')."\n\n"
            ."{$order->name}, {$order->phone}\n{$order->address_line1} {$order->address_line2}\n{$order->city}, {$order->state} {$order->pincode}";

        try {
            Mail::raw($body, fn ($m) => $m->to(config('site.contact.email'))->subject("New order {$order->number}")->replyTo($order->email, $order->name));
            Mail::raw("Thank you for your order.\n\n".$body, fn ($m) => $m->to($order->email, $order->name)->subject('Your order '.$order->number.' with '.config('site.full_name')));
        } catch (\Throwable $e) {
            Log::warning('Order mail failed', ['order' => $order->number, 'error' => $e->getMessage()]);
        }
    }
}
