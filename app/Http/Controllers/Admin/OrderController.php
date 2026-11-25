<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::withCount('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment')) {
            $query->where('payment_method', $request->payment);
        }
        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->where(fn ($q) => $q->where('number', 'like', $term)->orWhere('name', 'like', $term)
                ->orWhere('phone', 'like', $term)->orWhere('email', 'like', $term));
        }

        return view('admin.orders.index', [
            'orders' => $query->latest()->paginate(20)->withQueryString(),
            'counts' => Order::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status'),
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUS_LABELS))],
            'payment_status' => ['nullable', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $was = $order->status;
        $updates = [
            'status' => $data['status'],
            'tracking_number' => $data['tracking_number'] ?? null,
            'admin_notes' => $data['admin_notes'] ?? null,
        ];
        if (! empty($data['payment_status'])) {
            $updates['payment_status'] = $data['payment_status'];
        }

        if ($data['status'] === 'shipped' && ! $order->shipped_at) {
            $updates['shipped_at'] = now();
        }
        if ($data['status'] === 'delivered') {
            $updates['delivered_at'] = now();
            if ($order->payment_method === 'cod') {
                $updates['payment_status'] = 'paid';
            }
        }

        $order->update($updates);

        // Cancelling puts the stock back; un-cancelling takes it again.
        if ($was !== 'cancelled' && $order->status === 'cancelled') {
            $this->moveStock($order, +1);
        } elseif ($was === 'cancelled' && $order->status !== 'cancelled') {
            $this->moveStock($order, -1);
        }

        if ($was !== $order->status && in_array($order->status, ['packed', 'shipped', 'delivered', 'cancelled'], true)) {
            $this->notifyCustomer($order);
        }

        return back()->with('success', 'Order '.$order->number.' updated: '.$order->statusLabel().'.');
    }

    private function moveStock(Order $order, int $direction): void
    {
        foreach ($order->items as $item) {
            $book = Book::where('slug', $item->slug)->first();
            if (! $book) {
                continue;
            }
            if ($direction > 0) {
                $book->increment('stock', $item->qty);
                $book->decrement('sold_count', min($item->qty, $book->sold_count));
            } else {
                $book->decrement('stock', min($item->qty, $book->stock));
                $book->increment('sold_count', $item->qty);
            }
        }
    }

    private function notifyCustomer(Order $order): void
    {
        $lines = [
            'packed' => 'Your order is packed and will be handed to the courier soon.',
            'shipped' => 'Your order is on its way.'.($order->tracking_number ? ' Tracking number: '.$order->tracking_number.'.' : ''),
            'delivered' => 'Your order has been delivered. Thank you for choosing Shubhanga.',
            'cancelled' => 'Your order has been cancelled. If you paid online, the refund will reach you in 5 to 7 working days.',
        ];

        try {
            Mail::raw("Hello {$order->name},\n\n{$lines[$order->status]}\n\nOrder {$order->number}\nTrack it any time: ".route('orders.track'),
                fn ($m) => $m->to($order->email, $order->name)->subject("Order {$order->number}: ".$order->statusLabel()));
        } catch (\Throwable $e) {
            Log::warning('Order status mail failed', ['order' => $order->number, 'error' => $e->getMessage()]);
        }
    }
}
