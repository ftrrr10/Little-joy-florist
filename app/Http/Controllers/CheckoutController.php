<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    const DELIVERY_FEE = 25000; // Flat Rp 25.000 delivery fee

    /**
     * Display the checkout page.
     */
    public function index()
    {
        $user = auth()->user();
        $cart = $user->cart()->with('items.product.category')->first();

        // 1. Cart cannot be empty
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('catalogue.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // 2. Double-check stock before showing checkout form
        foreach ($cart->items as $item) {
            $product = $item->product;
            if (!$product || !$product->is_active || $product->trashed()) {
                return redirect()->route('cart.index')->with('error', "Produk {$item->product_name} sudah tidak aktif atau tidak tersedia.");
            }
            if ($item->quantity > $product->stock) {
                return redirect()->route('cart.index')->with('error', "Stok produk {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.");
            }
        }

        $items = $cart->items;
        $subtotal = $items->sum('subtotal');
        $deliveryFee = self::DELIVERY_FEE;
        $total = $subtotal + $deliveryFee;

        return Inertia::render('Customer/Checkout', [
            'items' => $items,
            'subtotal' => (float)$subtotal,
            'deliveryFee' => (float)$deliveryFee,
            'total' => (float)$total,
        ]);
    }

    /**
     * Store a newly created order.
     */
    public function store(CheckoutRequest $request)
    {
        $user = auth()->user();
        $cart = $user->cart()->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('catalogue.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        try {
            $order = DB::transaction(function () use ($request, $user, $cart) {
                // 1. Lock associated product rows for update to prevent race conditions
                $productIds = $cart->items->pluck('product_id')->unique();
                $products = Product::lockForUpdate()->whereIn('id', $productIds)->get()->keyBy('id');

                $subtotal = 0;

                // 2. Validate stock and active status inside locked transaction
                foreach ($cart->items as $item) {
                    $product = $products->get($item->product_id);

                    if (!$product || !$product->is_active) {
                        throw new \Exception("Produk {$item->product->name} sudah tidak tersedia atau dinonaktifkan.");
                    }

                    if ($item->quantity > $product->stock) {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.");
                    }

                    $subtotal += $product->price * $item->quantity;
                }

                // 3. Generate unique order number: LJ-YYYYMMDD-XXXX
                $prefix = 'LJ-' . now()->format('Ymd') . '-';
                $lastOrder = Order::where('order_number', 'like', "{$prefix}%")
                    ->lockForUpdate() // Prevent sequence number collisions
                    ->orderBy('order_number', 'desc')
                    ->first();

                $sequence = 1;
                if ($lastOrder) {
                    $parts = explode('-', $lastOrder->order_number);
                    $lastSeq = (int)end($parts);
                    $sequence = $lastSeq + 1;
                }
                $orderNumber = $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);

                // 4. Create Order
                $deliveryFee = self::DELIVERY_FEE;
                $total = $subtotal + $deliveryFee;

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                    'order_date' => now(),
                    'delivery_date' => $request->validated('delivery_date'),
                    'recipient_name' => $request->validated('recipient_name'),
                    'recipient_phone' => $request->validated('recipient_phone'),
                    'delivery_address' => $request->validated('delivery_address'),
                    'greeting_message' => $request->validated('greeting_message'),
                    'customer_note' => $request->validated('customer_note'),
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'total' => $total,
                    'payment_status' => 'pending',
                    'order_status' => 'pending_payment',
                ]);

                // 5. Create Order Items (Snapshots)
                foreach ($cart->items as $item) {
                    $product = $products->get($item->product_id);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name, // Snapshot
                        'unit_price' => $product->price,  // Snapshot
                        'quantity' => $item->quantity,
                        'subtotal' => $product->price * $item->quantity,
                    ]);
                }

                // 6. Create Initial Status History
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'previous_status' => null,
                    'current_status' => 'pending_payment',
                    'note' => 'Pesanan berhasil dibuat.',
                    'changed_by' => $user->id,
                ]);

                // 7. Clear the cart
                $cart->items()->delete();

                return $order;
            });

            return redirect()->route('checkout.success', $order->order_number);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the checkout success page.
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return Inertia::render('Customer/CheckoutSuccess', [
            'orderNumber' => $order->order_number,
            'total' => (float)$order->total,
        ]);
    }
}
