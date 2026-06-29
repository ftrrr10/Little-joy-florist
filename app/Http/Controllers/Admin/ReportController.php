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

        $dailyRevenue = Order::whereIn('order_status', ['paid', 'processing', 'ready', 'shipped', 'completed'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->translatedFormat('d M'),
                    'revenue' => (float) $item->revenue
                ];
            });

        return view('admin.reports.index', [
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
                'daily_revenue' => $dailyRevenue,
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
            'Content-Type'        => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=Laporan_Penjualan_' . $startDate . '_s_d_' . $endDate . '.xls',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write HTML structure for Excel with basic CSS styling
            fwrite($file, '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">');
            fwrite($file, '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>');
            fwrite($file, '<body>');
            fwrite($file, '<table border="1" style="font-family: Arial, sans-serif; font-size: 11pt; border-collapse: collapse;">');
            
            // Header Row
            fwrite($file, '<tr>');
            $headersList = [
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
            ];
            foreach ($headersList as $header) {
                fwrite($file, '<th style="background-color: #064E3B; color: #FFFFFF; font-weight: bold; padding: 6px; text-align: left; border: 1px solid #CCCCCC;">' . htmlspecialchars($header) . '</th>');
            }
            fwrite($file, '</tr>');

            $statusLabels = [
                'completed' => 'Selesai',
                'shipped' => 'Dikirim',
                'ready' => 'Siap',
                'processing' => 'Diproses',
                'paid' => 'Lunas',
                'waiting_verification' => 'Menunggu Verifikasi',
                'pending_payment' => 'Belum Bayar',
                'cancelled' => 'Batal',
                'rejected' => 'Ditolak',
            ];

            // Data Rows
            foreach ($orders as $order) {
                fwrite($file, '<tr>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($order->order_number) . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($order->created_at->format('Y-m-d H:i:s')) . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($order->user->name ?? 'Guest') . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($order->user->email ?? '') . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($order->user->phone ?? '') . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($order->recipient_name ?? '') . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($order->recipient_phone ?? '') . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($order->delivery_address ?? '') . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($order->delivery_date ? $order->delivery_date->format('Y-m-d') : '-') . '</td>');
                
                $orderStatusLabel = $statusLabels[$order->order_status] ?? $order->order_status;
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($orderStatusLabel) . '</td>');
                
                $payStatus = 'Belum Bayar';
                if ($order->payment_status === 'verified') $payStatus = 'Lunas (Verified)';
                elseif ($order->payment_status === 'waiting_verification') $payStatus = 'Menunggu Verifikasi';
                elseif ($order->payment_status === 'rejected') $payStatus = 'Ditolak';
                
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px;">' . htmlspecialchars($payStatus) . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px; text-align: right;">' . (int)$order->subtotal . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px; text-align: right;">' . (int)$order->delivery_fee . '</td>');
                fwrite($file, '<td style="border: 1px solid #CCCCCC; padding: 4px; text-align: right;">' . (int)$order->total . '</td>');
                fwrite($file, '</tr>');
            }

            fwrite($file, '</table>');
            fwrite($file, '</body>');
            fwrite($file, '</html>');
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
