<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CartController extends Controller
{
    const DELIVERY_FEE = 25000; // Flat Rp 25.000 delivery fee

    /**
     * Display the customer's cart.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Use a transaction to self-heal the cart
        DB::transaction(function () use ($user) {
            $cart = $user->cart;
            if ($cart) {
                foreach ($cart->items as $item) {
                    $product = $item->product;
                    // Self-healing: remove item if product is inactive or soft-deleted
                    if (!$product || !$product->is_active || $product->trashed()) {
                        $item->delete();
                    } else {
                        // Recalculate subtotal using current product price to prevent manipulation
                        $currentPrice = $product->price;
                        $newSubtotal = $currentPrice * $item->quantity;
                        
                        if ($item->unit_price != $currentPrice || $item->subtotal != $newSubtotal) {
                            $item->update([
                                'unit_price' => $currentPrice,
                                'subtotal' => $newSubtotal,
                            ]);
                        }
                    }
                }
            }
        });

        // Load fresh cart with items, products, and categories
        $cart = $user->cart()->with(['items.product.category'])->first();
        
        $items = $cart ? $cart->items : collect();
        $subtotal = $items->sum('subtotal');
        $deliveryFee = $subtotal > 0 ? self::DELIVERY_FEE : 0;
        $total = $subtotal + $deliveryFee;

        return view('customer.cart', [
            'cart' => $cart,
            'items' => $items,
            'subtotal' => (float)$subtotal,
            'deliveryFee' => (float)$deliveryFee,
            'total' => (float)$total,
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function store(CartItemRequest $request)
    {
        $user = auth()->user();
        $productId = $request->validated('product_id');
        $quantity = $request->validated('quantity');

        $product = Product::findOrFail($productId);

        return DB::transaction(function () use ($user, $product, $quantity) {
            // Get or create cart for the user
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Check if product already exists in cart
            $cartItem = $cart->items()->where('product_id', $product->id)->first();

            $existingQuantity = $cartItem ? $cartItem->quantity : 0;
            $newQuantity = $existingQuantity + $quantity;

            // Enforce stock limit rule
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', "Stok tidak mencukupi. Stok tersedia: {$product->stock}.");
            }

            if ($cartItem) {
                $cartItem->update([
                    'quantity' => $newQuantity,
                    'unit_price' => $product->price,
                    'subtotal' => $product->price * $newQuantity,
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $product->price * $quantity,
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        });
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // Authorize that the cart item belongs to the authenticated user's cart
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403, 'Aksi ini tidak sah.');
        }

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ], [
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.integer' => 'Jumlah barang harus berupa angka.',
            'quantity.min' => 'Jumlah barang minimal 1.',
        ]);

        $quantity = (int)$request->input('quantity');
        $product = $cartItem->product;

        // Enforce stock limit rule
        if ($quantity > $product->stock) {
            return redirect()->back()->with('error', "Stok tidak mencukupi. Stok tersedia: {$product->stock}.");
        }

        DB::transaction(function () use ($cartItem, $product, $quantity) {
            $cartItem->update([
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'subtotal' => $product->price * $quantity,
            ]);
        });

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(CartItem $cartItem)
    {
        // Authorize
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403, 'Aksi ini tidak sah.');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang.');
    }

    /**
     * Clear all items in the cart.
     */
    public function clear()
    {
        $cart = auth()->user()->cart;
        if ($cart) {
            $cart->items()->delete();
        }

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
