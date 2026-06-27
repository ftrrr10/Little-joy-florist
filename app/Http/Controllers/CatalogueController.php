<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get all active categories for the filter sidebar
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        // 2. Query active products
        $query = Product::with('category')->where('is_active', true);

        // 3. Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 4. Apply category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // 5. Apply availability filter (in stock only)
        if ($request->input('availability') === 'instock') {
            $query->where('stock', '>', 0);
        }

        // 6. Apply sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // 7. Paginate results (12 products per page for catalog grid)
        $products = $query->paginate(12)->withQueryString();

        return view('public.catalogue.index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'availability', 'sort']),
        ]);
    }

    /**
     * Display details of a specific product.
     */
    public function show(string $slug)
    {
        // Find by slug, including soft-deleted checks which are handled by Eloquent
        $product = Product::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $user = auth()->user();
        $isStaff = $user && ($user->role === 'admin' || $user->role === 'operator');

        // Security check: Guests and customers cannot view inactive products
        if (!$product->is_active && !$isStaff) {
            abort(404, 'Produk tidak ditemukan.');
        }

        // Fetch related products (same category, active, excluding self)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('public.catalogue.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
