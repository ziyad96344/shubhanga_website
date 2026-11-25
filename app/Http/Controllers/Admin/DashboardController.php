<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Enquiry;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $paid = fn () => Order::where('status', '!=', 'cancelled')->where('status', '!=', 'awaiting_payment');

        $thisMonth = $paid()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total');
        $lastMonth = $paid()->whereMonth('created_at', now()->subMonthNoOverflow()->month)->whereYear('created_at', now()->subMonthNoOverflow()->year)->sum('total');
        $orderCount = $paid()->count();
        $revenue = $paid()->sum('total');

        $stats = [
            'revenue' => $revenue,
            'this_month' => $thisMonth,
            'growth' => $lastMonth > 0 ? round(($thisMonth - $lastMonth) / $lastMonth * 100, 1) : ($thisMonth > 0 ? 100 : 0),
            'orders' => $orderCount,
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'new_orders' => Order::where('status', 'placed')->count(),
            'avg_order' => $orderCount ? (int) round($revenue / $orderCount) : 0,
            'books' => Book::count(),
            'active_books' => Book::active()->count(),
            'low_stock' => Book::active()->where('stock', '<=', 10)->count(),
            'new_enquiries' => Enquiry::where('status', 'new')->count(),
            'customers' => Order::distinct()->count('phone'),
        ];

        // Revenue and orders for the last 30 days.
        $daily = $paid()->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as day, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('day')->get()->keyBy('day');
        $chart = collect(range(29, 0))->map(function ($ago) use ($daily) {
            $day = now()->subDays($ago);
            $row = $daily->get($day->format('Y-m-d'));

            return ['label' => $day->format('d M'), 'revenue' => (int) ($row->revenue ?? 0), 'orders' => (int) ($row->orders ?? 0)];
        });

        $byStatus = Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');

        return view('admin.dashboard', [
            'stats' => $stats,
            'chart' => $chart,
            'byStatus' => $byStatus,
            'recentOrders' => Order::latest()->take(8)->get(),
            'lowStock' => Book::active()->where('stock', '<=', 10)->orderBy('stock')->take(6)->get(),
            'topBooks' => Book::where('sold_count', '>', 0)->orderByDesc('sold_count')->take(5)->get(),
            'recentEnquiries' => Enquiry::latest()->take(5)->get(),
        ]);
    }
}
