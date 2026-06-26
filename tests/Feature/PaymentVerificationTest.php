<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentVerificationTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private User $customer;
    private User $otherCustomer;
    private User $operator;
    private Product $product;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->category = Category::factory()->create();
        $this->customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $this->otherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $this->operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);
        
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'price' => 500000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'order_number' => 'LJ-20260627-0001',
            'user_id' => $this->customer->id,
            'order_date' => now(),
            'delivery_date' => now()->addDays(2),
            'recipient_name' => 'Budi',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Alamat Pengiriman',
            'subtotal' => 500000,
            'delivery_fee' => 25000,
            'total' => 525000,
            'payment_status' => 'pending',
            'order_status' => 'pending_payment',
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'unit_price' => $this->product->price,
            'quantity' => 2,
            'subtotal' => 1000000,
        ]);
    }

    public function test_guest_cannot_upload_payment_proof(): void
    {
        $this->get(route('customer.payments.create', $this->order->order_number))
            ->assertRedirect(route('login'));
            
        $this->post(route('customer.payments.store', $this->order->order_number))
            ->assertRedirect(route('login'));
    }

    public function test_customer_cannot_upload_payment_proof_for_other_customers_order(): void
    {
        $response = $this->actingAs($this->otherCustomer)->get(route('customer.payments.create', $this->order->order_number));
        $response->assertStatus(404);

        $file = UploadedFile::fake()->image('receipt.jpg');
        $response = $this->actingAs($this->otherCustomer)->post(route('customer.payments.store', $this->order->order_number), [
            'destination_bank' => 'BCA',
            'sender_bank' => 'Mandiri',
            'account_holder_name' => 'Other Customer',
            'amount' => 525000,
            'transfer_date' => now()->format('Y-m-d'),
            'proof_image' => $file,
        ]);
        $response->assertStatus(404);
    }

    public function test_customer_can_upload_payment_proof_for_their_own_order(): void
    {
        $file = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($this->customer)->post(route('customer.payments.store', $this->order->order_number), [
            'destination_bank' => 'BCA (123-456-7890 a/n Little Joy Jakarta)',
            'sender_bank' => 'Mandiri',
            'account_holder_name' => 'Customer Utama',
            'amount' => 525000,
            'transfer_date' => now()->format('Y-m-d'),
            'proof_image' => $file,
        ]);

        $response->assertRedirect(route('customer.orders.show', $this->order->order_number));
        $response->assertSessionHas('success', 'Bukti pembayaran berhasil diunggah. Pesanan sedang menunggu verifikasi.');

        // 1. Verify Payment created
        $payment = Payment::first();
        $this->assertNotNull($payment);
        $this->assertEquals($this->order->id, $payment->order_id);
        $this->assertEquals('waiting_verification', $payment->verification_status);
        $this->assertNotNull($payment->proof_path);
        Storage::disk('public')->assertExists($payment->proof_path);

        // 2. Verify Order status updated
        $this->order->refresh();
        $this->assertEquals('waiting_verification', $this->order->order_status);
        $this->assertEquals('waiting_verification', $this->order->payment_status);

        // 3. Verify status history recorded
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $this->order->id,
            'previous_status' => 'pending_payment',
            'current_status' => 'waiting_verification',
            'note' => 'Pelanggan telah mengunggah bukti pembayaran.',
            'changed_by' => $this->customer->id,
        ]);
    }

    public function test_payment_proof_upload_validation_rejects_large_files_and_invalid_formats(): void
    {
        // 1. Reject large file (e.g. 3MB)
        $largeFile = UploadedFile::fake()->image('huge_receipt.jpg')->size(3000);
        $response = $this->actingAs($this->customer)->post(route('customer.payments.store', $this->order->order_number), [
            'destination_bank' => 'BCA',
            'sender_bank' => 'Mandiri',
            'account_holder_name' => 'Customer',
            'amount' => 525000,
            'transfer_date' => now()->format('Y-m-d'),
            'proof_image' => $largeFile,
        ]);
        $response->assertSessionHasErrors(['proof_image']);

        // 2. Reject invalid mime type (e.g. pdf)
        $pdfFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');
        $response = $this->actingAs($this->customer)->post(route('customer.payments.store', $this->order->order_number), [
            'destination_bank' => 'BCA',
            'sender_bank' => 'Mandiri',
            'account_holder_name' => 'Customer',
            'amount' => 525000,
            'transfer_date' => now()->format('Y-m-d'),
            'proof_image' => $pdfFile,
        ]);
        $response->assertSessionHasErrors(['proof_image']);
    }

    public function test_operator_can_approve_payment_proof_which_reduces_stock_and_records_ledger(): void
    {
        // Setup order as waiting_verification with a payment record
        $this->order->update([
            'order_status' => 'waiting_verification',
            'payment_status' => 'waiting_verification',
        ]);
        
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'destination_bank' => 'BCA',
            'sender_bank' => 'Mandiri',
            'account_holder_name' => 'Customer',
            'amount' => 525000,
            'transfer_date' => now()->format('Y-m-d'),
            'proof_path' => 'proofs/receipt.jpg',
            'verification_status' => 'waiting_verification',
        ]);

        $response = $this->actingAs($this->operator)->post(route('operator.payments.verify', $this->order->order_number), [
            'action' => 'approve',
        ]);

        $response->assertRedirect(route('operator.orders.show', $this->order->order_number));
        $response->assertSessionHas('success', 'Pembayaran berhasil diverifikasi dan stok telah berkurang.');

        // 1. Verify Order updated to paid
        $this->order->refresh();
        $this->assertEquals('paid', $this->order->order_status);
        $this->assertEquals('verified', $this->order->payment_status);

        // 2. Verify Payment updated
        $payment->refresh();
        $this->assertEquals('verified', $payment->verification_status);
        $this->assertEquals($this->operator->id, $payment->verified_by);
        $this->assertNotNull($payment->verified_at);

        // 3. Verify Product stock is reduced (Order has quantity: 2, stock was 5 -> new stock 3)
        $this->assertEquals(3, $this->product->fresh()->stock);

        // 4. Verify Stock Movement is logged
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'movement_type' => 'out',
            'quantity' => 2,
            'stock_before' => 5,
            'stock_after' => 3,
            'reference_type' => 'Order',
            'reference_id' => $this->order->id,
            'created_by' => $this->operator->id,
        ]);

        // 5. Verify order history logged
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $this->order->id,
            'previous_status' => 'waiting_verification',
            'current_status' => 'paid',
            'note' => 'Pembayaran berhasil diverifikasi dan diterima.',
            'changed_by' => $this->operator->id,
        ]);
    }

    public function test_operator_can_reject_payment_proof_with_rejection_note(): void
    {
        // Setup order as waiting_verification with a payment record
        $this->order->update([
            'order_status' => 'waiting_verification',
            'payment_status' => 'waiting_verification',
        ]);
        
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'destination_bank' => 'BCA',
            'sender_bank' => 'Mandiri',
            'account_holder_name' => 'Customer',
            'amount' => 525000,
            'transfer_date' => now()->format('Y-m-d'),
            'proof_path' => 'proofs/receipt.jpg',
            'verification_status' => 'waiting_verification',
        ]);

        $response = $this->actingAs($this->operator)->post(route('operator.payments.verify', $this->order->order_number), [
            'action' => 'reject',
            'rejection_note' => 'Nominal transfer kurang Rp 50.000.',
        ]);

        $response->assertRedirect(route('operator.orders.show', $this->order->order_number));

        // 1. Verify Order updated to rejected
        $this->order->refresh();
        $this->assertEquals('rejected', $this->order->order_status);
        $this->assertEquals('rejected', $this->order->payment_status);

        // 2. Verify Payment updated
        $payment->refresh();
        $this->assertEquals('rejected', $payment->verification_status);
        $this->assertEquals('Nominal transfer kurang Rp 50.000.', $payment->rejection_note);

        // 3. Verify Product stock is NOT reduced
        $this->assertEquals(5, $this->product->fresh()->stock);

        // 4. Verify order history logged
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $this->order->id,
            'previous_status' => 'waiting_verification',
            'current_status' => 'rejected',
            'note' => 'Pembayaran ditolak. Alasan: Nominal transfer kurang Rp 50.000.',
            'changed_by' => $this->operator->id,
        ]);
    }

    public function test_payment_verification_rechecks_available_stock_and_rejects_if_insufficient(): void
    {
        // Setup order as waiting_verification with a payment record
        $this->order->update([
            'order_status' => 'waiting_verification',
            'payment_status' => 'waiting_verification',
        ]);
        
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'destination_bank' => 'BCA',
            'sender_bank' => 'Mandiri',
            'account_holder_name' => 'Customer',
            'amount' => 525000,
            'transfer_date' => now()->format('Y-m-d'),
            'proof_path' => 'proofs/receipt.jpg',
            'verification_status' => 'waiting_verification',
        ]);

        // Reduce stock in the background to 1 (Order requires quantity: 2)
        $this->product->update(['stock' => 1]);

        $response = $this->actingAs($this->operator)->post(route('operator.payments.verify', $this->order->order_number), [
            'action' => 'approve',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertTrue(str_contains(session('error'), "Stok produk {$this->product->name} tidak mencukupi. Stok tersedia: 1."));

        // Order remains in waiting_verification and stock remains 1
        $this->order->refresh();
        $this->assertEquals('waiting_verification', $this->order->order_status);
        $this->assertEquals(1, $this->product->fresh()->stock);
    }

    public function test_payment_double_verification_is_rejected(): void
    {
        // Setup order as paid (already approved)
        $this->order->update([
            'order_status' => 'paid',
            'payment_status' => 'verified',
        ]);
        
        Payment::create([
            'order_id' => $this->order->id,
            'destination_bank' => 'BCA',
            'sender_bank' => 'Mandiri',
            'account_holder_name' => 'Customer',
            'amount' => 525000,
            'transfer_date' => now()->format('Y-m-d'),
            'proof_path' => 'proofs/receipt.jpg',
            'verification_status' => 'verified',
        ]);

        // Attempting to verify again redirects back with error
        $response = $this->actingAs($this->operator)->post(route('operator.payments.verify', $this->order->order_number), [
            'action' => 'approve',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pesanan tidak sedang dalam proses menunggu verifikasi pembayaran.');
    }

    public function test_general_status_updates_enforces_strict_transition_rules(): void
    {
        // 1. Paid -> Processing: Allowed
        $this->order->update(['order_status' => 'paid']);
        $response = $this->actingAs($this->operator)->put(route('operator.orders.update-status', $this->order->order_number), [
            'order_status' => 'processing',
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals('processing', $this->order->fresh()->order_status);

        // 2. Processing -> Shipped: Blocked (must go through 'ready' first!)
        $response = $this->actingAs($this->operator)->put(route('operator.orders.update-status', $this->order->order_number), [
            'order_status' => 'shipped',
        ]);
        $response->assertSessionHas('error');
        $this->assertEquals('processing', $this->order->fresh()->order_status);

        // 3. Bypass via general status update to paid/rejected is blocked
        $this->order->update(['order_status' => 'waiting_verification']);
        $response = $this->actingAs($this->operator)->put(route('operator.orders.update-status', $this->order->order_number), [
            'order_status' => 'paid',
        ]);
        $response->assertSessionHas('error');
        $this->assertEquals('waiting_verification', $this->order->fresh()->order_status);
    }
}
