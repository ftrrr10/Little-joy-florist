<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard with metrics and trends.
     */
    public function index()
    {
        // 1. Core financial and operational metrics
        $totalSales = Order::whereIn('order_status', ['paid', 'processing', 'ready', 'shipped', 'completed'])
            ->sum('total');

        $ordersToday = Order::whereDate('created_at', Carbon::today())->count();
        
        $pendingPaymentCount = Order::where('order_status', 'pending_payment')->count();
        $waitingVerificationCount = Order::where('order_status', 'waiting_verification')->count();
        $processingCount = Order::where('order_status', 'processing')->count();
        $readyCount = Order::where('order_status', 'ready')->count();
        $shippedCount = Order::where('order_status', 'shipped')->count();
        $completedCount = Order::where('order_status', 'completed')->count();
        $cancelledCount = Order::where('order_status', 'cancelled')->count();
        $rejectedCount = Order::where('order_status', 'rejected')->count();

        // 2. Low stock products (stock <= 5)
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('is_active', true)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->get();

        // 3. Recent 5 orders
        $recentOrders = Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Set locale to Indonesian for trend labels
        Carbon::setLocale('id');

        // 4. Weekly Sales Trend (last 7 days including today)
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $formattedDate = $date->translatedFormat('d M'); // e.g., "27 Jun"

            $revenue = Order::whereIn('order_status', ['paid', 'processing', 'ready', 'shipped', 'completed'])
                ->whereDate('created_at', $dateString)
                ->sum('total');

            $weeklyTrend[] = [
                'date' => $dateString,
                'label' => $formattedDate,
                'revenue' => (float) $revenue,
            ];
        }

        // 5. Monthly Sales Trend (last 6 months including current month)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::today()->subMonths($i);
            $monthLabel = $monthDate->translatedFormat('F Y'); // e.g., "Juni 2026"
            $monthKey = $monthDate->format('Y-m');

            $revenue = Order::whereIn('order_status', ['paid', 'processing', 'ready', 'shipped', 'completed'])
                ->whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->sum('total');

            $monthlyTrend[] = [
                'month' => $monthKey,
                'label' => $monthLabel,
                'revenue' => (float) $revenue,
            ];
        }

        // 6. User and Staff metrics for Dashboard
        $totalCustomers = \App\Models\User::where('role', \App\Models\User::ROLE_CUSTOMER)->count();
        $totalOperators = \App\Models\User::where('role', \App\Models\User::ROLE_OPERATOR)->count();

        // 5 latest customers with order count and total spent
        $recentCustomers = \App\Models\User::where('role', \App\Models\User::ROLE_CUSTOMER)
            ->withCount('orders')
            ->withSum(['orders as total_spent' => function ($query) {
                $query->whereIn('payment_status', ['verified']);
            }], 'total')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentCustomers->transform(function ($customer) {
            $customer->total_spent = (float) ($customer->total_spent ?? 0);
            return $customer;
        });

        // Operators list with verified payment counts
        $operatorsList = \App\Models\User::where('role', \App\Models\User::ROLE_OPERATOR)
            ->orderBy('name', 'asc')
            ->get();

        $operatorsList->transform(function ($operator) {
            $operator->verified_payments_count = \App\Models\Payment::where('verified_by', $operator->id)->count();
            return $operator;
        });

        return view('admin.dashboard', [
            'metrics' => [
                'total_sales' => (float) $totalSales,
                'orders_today' => $ordersToday,
                'total_customers' => $totalCustomers,
                'total_operators' => $totalOperators,
                'status_counts' => [
                    'pending_payment' => $pendingPaymentCount,
                    'waiting_verification' => $waitingVerificationCount,
                    'processing' => $processingCount,
                    'ready' => $readyCount,
                    'shipped' => $shippedCount,
                    'completed' => $completedCount,
                    'cancelled' => $cancelledCount,
                    'rejected' => $rejectedCount,
                ],
            ],
            'low_stock_products' => $lowStockProducts,
            'recent_orders' => $recentOrders,
            'weekly_trend' => $weeklyTrend,
            'monthly_trend' => $monthlyTrend,
            'recent_customers' => $recentCustomers,
            'operators_list' => $operatorsList,
        ]);
    }
}
