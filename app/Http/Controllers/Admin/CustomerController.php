<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * The store has guest checkout, so a customer is everyone who has ordered
 * with the same phone number.
 */
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()
            ->selectRaw('phone, MAX(name) as name, MAX(email) as email, MAX(city) as city, MAX(state) as state, COUNT(*) as orders, SUM(CASE WHEN status NOT IN (\'cancelled\', \'awaiting_payment\') THEN total ELSE 0 END) as spent, MAX(created_at) as last_order')
            ->groupBy('phone');

        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->havingRaw('MAX(name) LIKE ? OR MAX(email) LIKE ? OR phone LIKE ?', [$term, $term, $term]);
        }

        return view('admin.customers.index', ['customers' => $query->orderByDesc('last_order')->paginate(25)->withQueryString()]);
    }

    public function show(string $phone)
    {
        $orders = Order::where('phone', $phone)->latest()->withCount('items')->get();
        abort_if($orders->isEmpty(), 404);

        return view('admin.customers.show', [
            'customer' => $orders->first(),
            'orders' => $orders,
            'spent' => $orders->whereNotIn('status', ['cancelled', 'awaiting_payment'])->sum('total'),
        ]);
    }
}
