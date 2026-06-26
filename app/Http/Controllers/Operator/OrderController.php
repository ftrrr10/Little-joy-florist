<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of all customer orders.
     */
    public function index()
    {
        $orders = Order::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Operator/OrderList', [
            'orders' => $orders,
        ]);
    }

    /**
     * Display the specified order detail.
     */
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['user', 'items.product.category', 'payment.verifier', 'histories.actor'])
            ->firstOrFail();

        return Inertia::render('Operator/OrderDetail', [
            'order' => $order,
        ]);
    }

    /**
     * Update the order status (general state transitions).
     */
    public function updateStatus(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $request->validate([
            'order_status' => ['required', 'string'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $newStatus = $request->input('order_status');
        $note = $request->input('note');
        $currentStatus = $order->order_status;

        // 1. Enforce strict allowed status transitions
        $allowedTransitions = [
            'pending_payment' => ['cancelled', 'waiting_verification'],
            'waiting_verification' => ['paid', 'rejected'],
            'rejected' => ['cancelled', 'waiting_verification'],
            'paid' => ['processing'],
            'processing' => ['ready'],
            'ready' => ['shipped'],
            'shipped' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            return redirect()->back()->with('error', "Transisi status dari {$currentStatus} ke {$newStatus} tidak sah.");
        }

        // 2. Prevent bypass of payment verification endpoint
        if (in_array($newStatus, ['paid', 'rejected'])) {
            return redirect()->back()->with('error', 'Penyetujuan atau penolakan pembayaran harus diproses melalui jalur Verifikasi Pembayaran.');
        }

        try {
            DB::transaction(function () use ($order, $newStatus, $currentStatus, $note) {
                // Lock order row
                $order->lockForUpdate();

                $updateData = ['order_status' => $newStatus];
                if ($newStatus === 'completed') {
                    $updateData['completed_at'] = now();
                }

                $order->update($updateData);

                // Default transition notes if empty
                $defaultNotes = [
                    'processing' => 'Pesanan sedang diproses dan dirangkai oleh florist.',
                    'ready' => 'Rangkaian bunga siap dikirim.',
                    'shipped' => 'Pesanan sedang dalam pengantaran oleh kurir.',
                    'completed' => 'Pesanan berhasil diserahkan kepada penerima.',
                    'cancelled' => 'Pesanan telah dibatalkan.',
                ];
                $historyNote = $note ?: ($defaultNotes[$newStatus] ?? "Status pesanan diubah ke {$newStatus}.");

                // Log History
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'previous_status' => $currentStatus,
                    'current_status' => $newStatus,
                    'note' => $historyNote,
                    'changed_by' => auth()->id(),
                ]);
            });

            return redirect()->route('operator.orders.show', $order->order_number)
                ->with('success', "Status pesanan berhasil diubah menjadi {$newStatus}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Verify or reject an uploaded bank transfer payment proof.
     */
    public function verifyPayment(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $payment = $order->payment()->firstOrFail();

        $request->validate([
            'action' => ['required', 'string', 'in:approve,reject'],
            'rejection_note' => ['required_if:action,reject', 'nullable', 'string', 'max:500'],
        ], [
            'action.required' => 'Aksi verifikasi wajib ditentukan.',
            'action.in' => 'Aksi tidak valid.',
            'rejection_note.required_if' => 'Alasan penolakan wajib ditulis jika pembayaran ditolak.',
            'rejection_note.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $action = $request->input('action');
        $rejectionNote = $request->input('rejection_note');

        // Check eligibility: Order must be in waiting_verification state
        if ($order->order_status !== 'waiting_verification') {
            return redirect()->back()->with('error', 'Pesanan tidak sedang dalam proses menunggu verifikasi pembayaran.');
        }

        try {
            DB::transaction(function () use ($order, $payment, $action, $rejectionNote) {
                // 1. Lock the order and payment rows
                $order->lockForUpdate();
                $payment->lockForUpdate();

                $oldStatus = $order->order_status;

                if ($action === 'approve') {
                    // 2. Lock products and verify stock availability before reducing
                    foreach ($order->items as $item) {
                        if (!$item->product_id) {
                            throw new \Exception("Produk {$item->product_name} tidak ditemukan (sudah dihapus). Penyetujuan dibatalkan.");
                        }

                        $product = Product::lockForUpdate()->findOrFail($item->product_id);

                        if (!$product->is_active) {
                            throw new \Exception("Produk {$product->name} saat ini sedang dinonaktifkan.");
                        }

                        if ($product->stock < $item->quantity) {
                            throw new \Exception("Stok produk {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.");
                        }

                        $stockBefore = $product->stock;
                        $stockAfter = $stockBefore - $item->quantity;

                        // 3. Decrement stock
                        $product->update(['stock' => $stockAfter]);

                        // 4. Log Stock Movement
                        StockMovement::create([
                            'product_id' => $product->id,
                            'movement_type' => 'out',
                            'quantity' => $item->quantity,
                            'stock_before' => $stockBefore,
                            'stock_after' => $stockAfter,
                            'reference_type' => 'Order',
                            'reference_id' => $order->id,
                            'note' => "Pengurangan stok otomatis setelah pembayaran pesanan #{$order->order_number} diverifikasi.",
                            'created_by' => auth()->id(),
                        ]);
                    }

                    // 5. Update Payment to verified
                    $payment->update([
                        'verification_status' => 'verified',
                        'verified_by' => auth()->id(),
                        'verified_at' => now(),
                        'rejection_note' => null,
                    ]);

                    // 6. Update Order status
                    $order->update([
                        'payment_status' => 'verified',
                        'order_status' => 'paid',
                    ]);

                    // 7. Log Order Status History
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'previous_status' => $oldStatus,
                        'current_status' => 'paid',
                        'note' => 'Pembayaran berhasil diverifikasi dan diterima.',
                        'changed_by' => auth()->id(),
                    ]);

                } else { // Action is reject
                    // 2. Update Payment to rejected
                    $payment->update([
                        'verification_status' => 'rejected',
                        'rejection_note' => $rejectionNote,
                    ]);

                    // 3. Update Order status
                    $order->update([
                        'payment_status' => 'rejected',
                        'order_status' => 'rejected',
                    ]);

                    // 4. Log Order Status History
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'previous_status' => $oldStatus,
                        'current_status' => 'rejected',
                        'note' => 'Pembayaran ditolak. Alasan: ' . $rejectionNote,
                        'changed_by' => auth()->id(),
                    ]);
                }
            });

            $msg = $action === 'approve' ? 'Pembayaran berhasil diverifikasi dan stok telah berkurang.' : 'Pembayaran ditolak dan pemberitahuan telah dikirim ke pelanggan.';
            return redirect()->route('operator.orders.show', $order->order_number)->with('success', $msg);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses verifikasi pembayaran: ' . $e->getMessage());
        }
    }
}
