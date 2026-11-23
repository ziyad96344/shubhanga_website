<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /** Confirmation page, only for orders placed in this browser session. */
    public function show(string $number)
    {
        abort_unless(in_array($number, session('my_orders', []), true), 404);

        $order = Order::with('items')->where('number', $number)->firstOrFail();

        return view('orders.show', ['order' => $order, 'justPlaced' => true]);
    }

    public function trackForm()
    {
        return view('orders.track');
    }

    /** Look up an order by number + the phone used to place it. */
    public function track(Request $request)
    {
        $data = $request->validate([
            'number' => ['required', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:15'],
        ], ['required' => 'Please fill in this field.']);

        $phone = substr(preg_replace('/\D/', '', $data['phone']), -10);

        $order = Order::with('items')
            ->where('number', strtoupper(trim($data['number'])))
            ->where('phone', $phone)
            ->first();

        if (! $order) {
            return back()->withInput()->with('not_found', true);
        }

        return view('orders.show', ['order' => $order, 'justPlaced' => false]);
    }
}
