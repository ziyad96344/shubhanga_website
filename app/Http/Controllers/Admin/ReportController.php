<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->filled('from') ? Carbon::parse($request->from)->startOfDay() : now()->subDays(29)->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->to)->endOfDay() : now()->endOfDay();

        $base = fn () => Order::whereNotIn('status', ['cancelled', 'awaiting_payment'])->whereBetween('created_at', [$from, $to]);

        $count = $base()->count();
        $summary = [
            'orders' => $count,
            'revenue' => $base()->sum('total'),
            'avg' => $count ? (int) round($base()->avg('total')) : 0,
            'discount' => $base()->sum('discount'),
            'cod' => $base()->where('payment_method', 'cod')->count(),
            'online' => $base()->where('payment_method', 'online')->count(),
            'books_sold' => OrderItem::whereIn('order_id', $base()->select('id'))->sum('qty'),
        ];

        $daily = $base()->selectRaw('DATE(created_at) as day, COUNT(*) as orders, SUM(total) as revenue')
            ->groupBy('day')->orderBy('day')->get();

        $topBooks = OrderItem::whereIn('order_id', $base()->select('id'))
            ->selectRaw('slug, MAX(name) as name, MAX(meta) as meta, SUM(qty) as qty, SUM(line_total) as revenue')
            ->groupBy('slug')->orderByDesc('qty')->take(10)->get();

        return view('admin.reports.sales', [
            'summary' => $summary,
            'daily' => $daily,
            'topBooks' => $topBooks,
            'orders' => $base()->latest()->paginate(30)->withQueryString(),
            'from' => $from,
            'to' => $to,
        ]);
    }
}
