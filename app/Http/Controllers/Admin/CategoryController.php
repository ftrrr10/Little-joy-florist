<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Category::class);

        $categories = Category::withCount('products')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/CategoryList', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): Response
    {
        Gate::authorize('create', Category::class);

        return Inertia::render('Admin/CategoryForm', [
            'category' => null,
        ]);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        Category::create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $kategori): Response
    {
        Gate::authorize('update', $kategori);

        return Inertia::render('Admin/CategoryForm', [
            'category' => $kategori,
        ]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(CategoryRequest $request, Category $kategori): RedirectResponse
    {
        Gate::authorize('update', $kategori);

        $kategori->update($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $kategori): RedirectResponse
    {
        Gate::authorize('delete', $kategori);

        // Relational safety check: do not delete categories with products
        if ($kategori->products()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk di dalamnya.');
        }

        $kategori->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
