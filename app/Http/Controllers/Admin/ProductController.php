<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Product::class);

        $products = Product::with('category')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/ProductList', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): Response
    {
        Gate::authorize('create', Product::class);

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/ProductForm', [
            'product' => null,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        $validatedData = $request->validated();

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validatedData['image_path'] = $path;
        }

        // Remove the temporary 'image' file from array since we store 'image_path'
        unset($validatedData['image']);

        Product::create($validatedData);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $produk): Response
    {
        Gate::authorize('update', $produk);

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/ProductForm', [
            'product' => $produk,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, Product $produk): RedirectResponse
    {
        Gate::authorize('update', $produk);

        $validatedData = $request->validated();

        // Handle image upload if a new one is provided
        if ($request->hasFile('image')) {
            // Delete the old image file if it exists
            if ($produk->image_path) {
                Storage::disk('public')->delete($produk->image_path);
            }

            $path = $request->file('image')->store('products', 'public');
            $validatedData['image_path'] = $path;
        }

        unset($validatedData['image']);

        $produk->update($validatedData);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $produk): RedirectResponse
    {
        Gate::authorize('delete', $produk);

        // Soft delete the product
        $produk->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus (soft delete).');
    }
}
