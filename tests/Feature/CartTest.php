<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private User $customer;
    private User $otherCustomer;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $this->otherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_view_cart_or_perform_cart_actions(): void
    {
        // View cart
        $this->get(route('cart.index'))->assertRedirect(route('login'));

        // Add to cart
        $this->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ])->assertRedirect(route('login'));

        // Since no cart exists, we create a mock cart item to test update/delete
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => $this->product->price,
            'subtotal' => $this->product->price * 2,
        ]);

        // Update cart item
        $this->put(route('cart.update', $cartItem->id), [
            'quantity' => 3,
        ])->assertRedirect(route('login'));

        // Delete cart item
        $this->delete(route('cart.destroy', $cartItem->id))->assertRedirect(route('login'));

        // Clear cart
        $this->delete(route('cart.clear'))->assertRedirect(route('login'));
    }

    public function test_customer_can_view_cart_and_get_correct_pricing(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100000,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($this->customer)->get(route('cart.index'));
        
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Cart')
            ->where('subtotal', 200000)
            ->where('deliveryFee', 25000)
            ->where('total', 225000)
        );
    }

    public function test_customer_can_add_item_to_cart(): void
    {
        $response = $this->actingAs($this->customer)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Produk berhasil ditambahkan ke keranjang.');

        $this->assertDatabaseHas('carts', ['user_id' => $this->customer->id]);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100000.00,
            'subtotal' => 200000.00,
        ]);
    }

    public function test_adding_duplicate_item_merges_quantity_and_updates_subtotal(): void
    {
        // Add first time
        $this->actingAs($this->customer)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        // Add second time (duplicate)
        $response = $this->actingAs($this->customer)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);

        $response->assertRedirect(route('cart.index'));

        // Cart items count should still be 1, but quantity should be 5
        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 5,
            'unit_price' => 100000.00,
            'subtotal' => 500000.00,
        ]);
    }

    public function test_cannot_add_quantity_exceeding_product_stock(): void
    {
        // Stock is 10, try to add 11
        $response = $this->actingAs($this->customer)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 11,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Stok tidak mencukupi. Stok tersedia: 10.');
        $this->assertDatabaseMissing('cart_items', ['product_id' => $this->product->id]);
    }

    public function test_adding_duplicate_exceeding_stock_is_rejected(): void
    {
        // Add 6 items (stock is 10)
        $this->actingAs($this->customer)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 6,
        ]);

        // Try adding 5 more (total 11)
        $response = $this->actingAs($this->customer)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Stok tidak mencukupi. Stok tersedia: 10.');
        
        // Quantity should remain 6
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 6,
        ]);
    }

    public function test_customer_can_update_cart_item_quantity_within_stock(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100000,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($this->customer)->put(route('cart.update', $cartItem->id), [
            'quantity' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Keranjang berhasil diperbarui.');
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 5,
            'subtotal' => 500000.00,
        ]);
    }

    public function test_customer_cannot_update_cart_item_quantity_beyond_stock(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100000,
            'subtotal' => 200000,
        ]);

        // Try updating to 11 (stock is 10)
        $response = $this->actingAs($this->customer)->put(route('cart.update', $cartItem->id), [
            'quantity' => 11,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Stok tidak mencukupi. Stok tersedia: 10.');
        
        // Quantity should remain 2
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 2,
        ]);
    }

    public function test_customer_cannot_update_other_customers_cart_item(): void
    {
        $cart = Cart::create(['user_id' => $this->otherCustomer->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100000,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($this->customer)->put(route('cart.update', $cartItem->id), [
            'quantity' => 5,
        ]);

        $response->assertStatus(403);
    }

    public function test_customer_can_delete_cart_item(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100000,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($this->customer)->delete(route('cart.destroy', $cartItem->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Barang berhasil dihapus dari keranjang.');
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_customer_cannot_delete_other_customers_cart_item(): void
    {
        $cart = Cart::create(['user_id' => $this->otherCustomer->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100000,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($this->customer)->delete(route('cart.destroy', $cartItem->id));

        $response->assertStatus(403);
    }

    public function test_customer_can_clear_cart(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100000,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($this->customer)->delete(route('cart.clear'));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Keranjang berhasil dikosongkan.');
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_server_side_subtotal_recalculation_upon_view(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100000, // old price
            'subtotal' => 200000,
        ]);

        // Change the product price in the database
        $this->product->update(['price' => 120000]); // new price

        // Visit the cart page, which triggers the self-healing and price recalculation
        $response = $this->actingAs($this->customer)->get(route('cart.index'));

        $response->assertStatus(200);
        
        // Assert that the stored database values are updated
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'unit_price' => 120000.00,
            'subtotal' => 240000.00,
        ]);

        // Assert that the view props receive the updated total
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Cart')
            ->where('subtotal', 240000)
            ->where('deliveryFee', 25000)
            ->where('total', 265000)
        );
    }

    public function test_inactive_and_deleted_products_are_purged_upon_view(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        
        $inactiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => false,
            'stock' => 5,
        ]);
        $deletedProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
            'stock' => 5,
        ]);

        $item1 = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $inactiveProduct->id,
            'quantity' => 1,
            'unit_price' => $inactiveProduct->price,
            'subtotal' => $inactiveProduct->price,
        ]);

        $item2 = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $deletedProduct->id,
            'quantity' => 1,
            'unit_price' => $deletedProduct->price,
            'subtotal' => $deletedProduct->price,
        ]);

        // Soft delete the second product
        $deletedProduct->delete();

        // Visit cart
        $response = $this->actingAs($this->customer)->get(route('cart.index'));
        $response->assertStatus(200);

        // Verify that the cart items for inactive and deleted products are automatically deleted from database
        $this->assertDatabaseMissing('cart_items', ['id' => $item1->id]);
        $this->assertDatabaseMissing('cart_items', ['id' => $item2->id]);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_operator_and_admin_can_access_cart_and_perform_cart_actions(): void
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        // 1. Operator can add item to cart
        $response = $this->actingAs($operator)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('carts', ['user_id' => $operator->id]);

        // 2. Admin can add item to cart
        $response = $this->actingAs($admin)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);
        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('carts', ['user_id' => $admin->id]);
    }
}
