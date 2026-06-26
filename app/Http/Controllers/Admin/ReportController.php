<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display the Sales Report page with filters and compiled metrics.
     */
    public function index(Request $request)
    {
        // 1. Parse and validate filters
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $orderStatus = $request->input('order_status', 'all');
        $paymentStatus = $request->input('payment_status', 'all');

        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'order_status' => ['nullable', 'string'],
            'payment_status' => ['nullable', 'string'],
        ]);

        // 2. Build order query with filters
        $query = Order::with(['user', 'items'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($orderStatus !== 'all') {
            $query->where('order_status', $orderStatus);
        }

        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }

        // Get total orders matching filters (for total transactions count)
        $orders = $query->orderBy('created_at', 'desc')->get();
        $totalTransactions = $orders->count();

        // 3. Compute realized financial metrics (only for orders that are paid/processing/ready/shipped/completed)
        $revenueQuery = clone $query;
        $totalRevenue = $revenueQuery->whereIn('order_status', ['paid', 'processing', 'ready', 'shipped', 'completed'])
            ->sum('total');

        // 4. Compute items sold and best selling products
        $successfulOrderIds = clone $query;
        $successfulOrderIds = $successfulOrderIds->whereIn('order_status', ['paid', 'processing', 'ready', 'shipped', 'completed'])
            ->pluck('id');

        $totalItemsSold = OrderItem::whereIn('order_id', $successfulOrderIds)->sum('quantity');

        $bestSellers = OrderItem::whereIn('order_id', $successfulOrderIds)
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->groupBy('product_name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Admin/SalesReport', [
            'orders' => $orders,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'order_status' => $orderStatus,
                'payment_status' => $paymentStatus,
            ],
            'summary' => [
                'total_transactions' => $totalTransactions,
                'total_revenue' => (float) $totalRevenue,
                'total_items_sold' => (int) $totalItemsSold,
                'best_sellers' => $bestSellers,
            ]
        ]);
    }

    /**
     * Export the filtered sales report dataset as a CSV file.
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $orderStatus = $request->input('order_status', 'all');
        $paymentStatus = $request->input('payment_status', 'all');

        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'order_status' => ['nullable', 'string'],
            'payment_status' => ['nullable', 'string'],
        ]);

        $query = Order::with(['user'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($orderStatus !== 'all') {
            $query->where('order_status', $orderStatus);
        }

        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Laporan_Penjualan_' . $startDate . '_s_d_' . $endDate . '.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Column Headers
            fputcsv($file, [
                'Nomor Pesanan',
                'Tanggal Pesanan',
                'Nama Pelanggan',
                'Email Pelanggan',
                'Telepon Pelanggan',
                'Nama Penerima',
                'Telepon Penerima',
                'Alamat Pengiriman',
                'Tanggal Pengiriman',
                'Status Pesanan',
                'Status Pembayaran',
                'Subtotal (Rp)',
                'Ongkos Kirim (Rp)',
                'Total Omset (Rp)'
            ]);

            // Data Rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->user->name,
                    $order->user->email,
                    $order->user->phone,
                    $order->recipient_name,
                    $order->recipient_phone,
                    $order->delivery_address,
                    $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '-',
                    $order->order_status,
                    $order->payment_status,
                    $order->subtotal,
                    $order->delivery_fee,
                    $order->total
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
