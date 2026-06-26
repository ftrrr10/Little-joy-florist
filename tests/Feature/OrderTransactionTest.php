<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTransactionTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private User $customer;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'price' => 500000,
            'stock' => 5,
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_checkout_or_place_order(): void
    {
        $this->get(route('checkout.index'))->assertRedirect(route('login'));
        
        $this->post(route('checkout.store'), [
            'recipient_name' => 'Recipient',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Test Address',
            'delivery_date' => now()->addDays(1)->format('Y-m-d'),
        ])->assertRedirect(route('login'));
    }

    public function test_customer_cannot_view_checkout_if_cart_is_empty(): void
    {
        $response = $this->actingAs($this->customer)->get(route('checkout.index'));
        $response->assertRedirect(route('catalogue.index'));
        $response->assertSessionHas('error', 'Keranjang belanja Anda kosong.');
    }

    public function test_customer_can_view_checkout_if_cart_has_items(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => $this->product->price,
            'subtotal' => $this->product->price * 2,
        ]);

        $response = $this->actingAs($this->customer)->get(route('checkout.index'));
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Checkout')
            ->where('subtotal', 1000000)
            ->where('deliveryFee', 25000)
            ->where('total', 1025000)
        );
    }

    public function test_checkout_enforces_product_stock_availability(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        
        // Cart has 6 items, but stock is 5
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 6,
            'unit_price' => $this->product->price,
            'subtotal' => $this->product->price * 6,
        ]);

        // Trying to view checkout redirects back to cart
        $response = $this->actingAs($this->customer)->get(route('checkout.index'));
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', "Stok produk {$this->product->name} tidak mencukupi. Stok tersedia: 5.");

        // Trying to submit checkout gets rejected
        $response = $this->actingAs($this->customer)->post(route('checkout.store'), [
            'recipient_name' => 'Budi',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Alamat Pengiriman',
            'delivery_date' => now()->addDays(1)->format('Y-m-d'),
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('error', "Stok produk {$this->product->name} tidak mencukupi. Stok tersedia: 5.");
    }

    public function test_checkout_succeeds_under_transaction_generates_order_number_saves_snapshots_and_clears_cart(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => $this->product->price,
            'subtotal' => $this->product->price * 2,
        ]);

        $deliveryDate = now()->addDays(2)->format('Y-m-d');

        $response = $this->actingAs($this->customer)->post(route('checkout.store'), [
            'recipient_name' => 'Budi Santoso',
            'recipient_phone' => '081299998888',
            'delivery_address' => 'Jalan Kebagusan Raya No. 12',
            'delivery_date' => $deliveryDate,
            'greeting_message' => 'Selamat Sukses!',
            'customer_note' => 'Pita warna emas.',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);

        $response->assertRedirect(route('checkout.success', $order->order_number));

        // 1. Verify Order fields
        $expectedOrderNumber = 'LJ-' . now()->format('Ymd') . '-0001';
        $this->assertEquals($expectedOrderNumber, $order->order_number);
        $this->assertEquals($this->customer->id, $order->user_id);
        $this->assertEquals('Budi Santoso', $order->recipient_name);
        $this->assertEquals('081299998888', $order->recipient_phone);
        $this->assertEquals('pending_payment', $order->order_status);
        $this->assertEquals('pending', $order->payment_status);
        $this->assertEquals(1000000.00, $order->subtotal);
        $this->assertEquals(25000.00, $order->delivery_fee);
        $this->assertEquals(1025000.00, $order->total);

        // 2. Verify snapshot in Order Items
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name, // Snapshot
            'unit_price' => 500000.00, // Snapshot
            'quantity' => 2,
            'subtotal' => 1000000.00,
        ]);

        // 3. Verify stock is NOT reduced at checkout
        $this->assertEquals(5, $this->product->fresh()->stock);

        // 4. Verify cart is cleared
        $this->assertDatabaseCount('cart_items', 0);

        // 5. Verify status history logged
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'previous_status' => null,
            'current_status' => 'pending_payment',
            'note' => 'Pesanan berhasil dibuat.',
            'changed_by' => $this->customer->id,
        ]);
    }

    public function test_consecutive_checkouts_generate_sequential_order_numbers(): void
    {
        // First checkout
        $cart1 = Cart::create(['user_id' => $this->customer->id]);
        CartItem::create([
            'cart_id' => $cart1->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'unit_price' => $this->product->price,
            'subtotal' => $this->product->price,
        ]);

        $this->actingAs($this->customer)->post(route('checkout.store'), [
            'recipient_name' => 'Budi',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Address 1',
            'delivery_date' => now()->addDays(1)->format('Y-m-d'),
        ]);

        // Second customer checkout
        $customer2 = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $cart2 = Cart::create(['user_id' => $customer2->id]);
        CartItem::create([
            'cart_id' => $cart2->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'unit_price' => $this->product->price,
            'subtotal' => $this->product->price,
        ]);

        $this->actingAs($customer2)->post(route('checkout.store'), [
            'recipient_name' => 'Andi',
            'recipient_phone' => '081234567891',
            'delivery_address' => 'Address 2',
            'delivery_date' => now()->addDays(1)->format('Y-m-d'),
        ]);

        $order1 = Order::where('recipient_name', 'Budi')->first();
        $order2 = Order::where('recipient_name', 'Andi')->first();

        $prefix = 'LJ-' . now()->format('Ymd') . '-';
        $this->assertEquals($prefix . '0001', $order1->order_number);
        $this->assertEquals($prefix . '0002', $order2->order_number);
    }

    public function test_operator_and_admin_can_checkout_successfully(): void
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        foreach ([$operator, $admin] as $user) {
            $cart = Cart::create(['user_id' => $user->id]);
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $this->product->id,
                'quantity' => 1,
                'unit_price' => $this->product->price,
                'subtotal' => $this->product->price,
            ]);

            $response = $this->actingAs($user)->post(route('checkout.store'), [
                'recipient_name' => 'Penerima Manual ' . $user->role,
                'recipient_phone' => '081299998888',
                'delivery_address' => 'Jalan Kebagusan Raya No. 12',
                'delivery_date' => now()->addDays(2)->format('Y-m-d'),
                'greeting_message' => 'Selamat!',
                'customer_note' => 'Catatan staf.',
            ]);

            $order = Order::where('user_id', $user->id)->first();
            $this->assertNotNull($order);
            $response->assertRedirect(route('checkout.success', $order->order_number));
            $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
        }
    }
}
