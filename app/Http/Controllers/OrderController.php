<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.orders.index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Display the customer's order detail view.
     */
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items.product.category', 'payment.verifier', 'histories.actor'])
            ->firstOrFail();

        return view('customer.orders.show', [
            'order' => $order,
        ]);
    }

    /**
     * Cancel an eligible order.
     */
    public function cancel($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Cancellation is only allowed for unpaid or rejected orders
        if (!in_array($order->order_status, ['pending_payment', 'rejected'])) {
            return redirect()->back()->with('error', 'Pesanan yang sudah dibayar atau sedang dalam proses tidak dapat dibatalkan.');
        }

        try {
            DB::transaction(function () use ($order) {
                // Lock order row
                $order->lockForUpdate();

                $oldStatus = $order->order_status;

                // Update Order status to cancelled
                $order->update([
                    'order_status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);

                // Record Status History
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'previous_status' => $oldStatus,
                    'current_status' => 'cancelled',
                    'note' => 'Pesanan dibatalkan oleh pelanggan.',
                    'changed_by' => auth()->id(),
                ]);
            });

            return redirect()->route('customer.orders.show', $order->order_number)
                ->with('success', 'Pesanan Anda berhasil dibatalkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
}
