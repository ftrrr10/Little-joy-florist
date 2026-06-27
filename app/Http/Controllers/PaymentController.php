<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Show the payment proof upload form.
     */
    public function create($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Check if order is eligible for payment upload
        if (!in_array($order->order_status, ['pending_payment', 'rejected'])) {
            return redirect()->route('customer.orders.show', $order->order_number)
                ->with('error', 'Pesanan ini tidak memerlukan unggah bukti pembayaran.');
        }

        return view('customer.payments.create', [
            'order' => $order,
        ]);
    }

    /**
     * Store the uploaded payment proof.
     */
    public function store(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Enforce eligibility
        if (!in_array($order->order_status, ['pending_payment', 'rejected'])) {
            return redirect()->route('customer.orders.show', $order->order_number)
                ->with('error', 'Pesanan ini tidak memerlukan unggah bukti pembayaran.');
        }

        // Validate payment proof details
        $request->validate([
            'destination_bank' => ['required', 'string', 'max:255'],
            'sender_bank' => ['required', 'string', 'max:255'],
            'account_holder_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
            'transfer_date' => ['required', 'date', 'before_or_equal:today'],
            'proof_image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'], // Max 2MB
        ], [
            'destination_bank.required' => 'Bank tujuan wajib diisi.',
            'sender_bank.required' => 'Bank pengirim wajib diisi.',
            'account_holder_name.required' => 'Nama pemilik rekening wajib diisi.',
            'amount.required' => 'Jumlah transfer wajib diisi.',
            'amount.numeric' => 'Jumlah transfer harus berupa angka.',
            'amount.min' => 'Jumlah transfer minimal Rp 1.',
            'transfer_date.required' => 'Tanggal transfer wajib diisi.',
            'transfer_date.date' => 'Format tanggal transfer tidak valid.',
            'transfer_date.before_or_equal' => 'Tanggal transfer tidak boleh di masa depan.',
            'proof_image.required' => 'Foto bukti transfer wajib diunggah.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'proof_image.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        try {
            DB::transaction(function () use ($request, $order) {
                // Lock the order row
                $order->lockForUpdate();

                $payment = $order->payment;
                
                // If re-uploading, delete old proof file from disk
                if ($payment && $payment->proof_path) {
                    Storage::disk('public')->delete($payment->proof_path);
                }

                // Upload new file
                $path = $request->file('proof_image')->store('proofs', 'public');

                // Create or update Payment record
                $paymentData = [
                    'order_id' => $order->id,
                    'destination_bank' => $request->input('destination_bank'),
                    'sender_bank' => $request->input('sender_bank'),
                    'account_holder_name' => $request->input('account_holder_name'),
                    'amount' => $request->input('amount'),
                    'transfer_date' => $request->input('transfer_date'),
                    'proof_path' => $path,
                    'verification_status' => 'waiting_verification',
                    'rejection_note' => null, // Reset rejection note
                ];

                if ($payment) {
                    $payment->update($paymentData);
                } else {
                    Payment::create($paymentData);
                }

                $oldStatus = $order->order_status;

                // Update Order status
                $order->update([
                    'payment_status' => 'waiting_verification',
                    'order_status' => 'waiting_verification',
                ]);

                // Record Status History
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'previous_status' => $oldStatus,
                    'current_status' => 'waiting_verification',
                    'note' => 'Pelanggan telah mengunggah bukti pembayaran.',
                    'changed_by' => auth()->id(),
                ]);
            });

            return redirect()->route('customer.orders.show', $order->order_number)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Pesanan sedang menunggu verifikasi.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses unggah bukti pembayaran: ' . $e->getMessage());
        }
    }
}
