<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StockController extends Controller
{
    /**
     * Display the stock overview and movement history.
     */
    public function index()
    {
        // 1. Get all active products with their category
        $products = Product::with('category')
            ->orderBy('name', 'asc')
            ->get();

        // 2. Get recent 30 stock movements
        $movements = StockMovement::with(['product.category', 'actor'])
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        return view('operator.stock.index', [
            'products' => $products,
            'movements' => $movements,
        ]);
    }

    /**
     * Perform a manual stock adjustment.
     */
    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'not_in:0'],
            'note' => ['required', 'string', 'max:500'],
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak valid.',
            'quantity.required' => 'Jumlah penyesuaian wajib diisi.',
            'quantity.integer' => 'Jumlah penyesuaian harus berupa angka bulat.',
            'quantity.not_in' => 'Jumlah penyesuaian tidak boleh nol.',
            'note.required' => 'Alasan penyesuaian wajib diisi.',
            'note.max' => 'Alasan penyesuaian maksimal 500 karakter.',
        ]);

        $productId = $request->input('product_id');
        $adjustmentQty = (int)$request->input('quantity'); // Positive for add, negative for subtract
        $note = $request->input('note');

        try {
            DB::transaction(function () use ($productId, $adjustmentQty, $note) {
                // 1. Lock the product row to prevent race conditions
                $product = Product::lockForUpdate()->findOrFail($productId);

                $stockBefore = $product->stock;
                $stockAfter = $stockBefore + $adjustmentQty;

                // 2. Enforce stock cannot go below zero
                if ($stockAfter < 0) {
                    throw new \Exception("Stok tidak boleh bernilai negatif. Sisa stok saat ini: {$stockBefore}.");
                }

                // 3. Update the product stock
                $product->update(['stock' => $stockAfter]);

                // 4. Record the stock movement log
                StockMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'adjustment',
                    'quantity' => abs($adjustmentQty),
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference_type' => 'Adjustment',
                    'reference_id' => null,
                    'note' => $note,
                    'created_by' => auth()->id(),
                ]);
            });

            return redirect()->back()->with('success', 'Penyesuaian stok berhasil disimpan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyesuaikan stok: ' . $e->getMessage());
        }
    }
}
