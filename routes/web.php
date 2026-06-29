<?php

use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Operator\OrderController as OperatorOrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $featuredProducts = \App\Models\Product::where('is_active', true)
        ->with('category')
        ->latest()
        ->take(4)
        ->get();
    $categories = \App\Models\Category::where('is_active', true)->get();
    return view('public.home', [
        'featuredProducts' => $featuredProducts,
        'categories' => $categories,
    ]);
})->name('home');

// Public Florist Catalog Routes
Route::get('/katalog', [CatalogueController::class, 'index'])->name('catalogue.index');
Route::get('/katalog/{slug}', [CatalogueController::class, 'show'])->name('catalogue.show');

Route::get('/tentang-kami', function () {
    return view('public.about');
})->name('about');

Route::get('/kontak', function () {
    return view('public.contact');
})->name('contact');

/*
|--------------------------------------------------------------------------
| Shared Authenticated Routes
|--------------------------------------------------------------------------
|
*/
Route::middleware('auth')->group(function () {
    // Smart dashboard redirector based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user && $user->role === 'operator') {
            return redirect()->route('operator.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Default Profile routes (for editing and account deletion)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Redirect old /profil route to /profile for all roles to prevent 403s
    Route::get('/profil', function () {
        return redirect()->route('profile.edit');
    })->name('customer.profile');
});

/*
|--------------------------------------------------------------------------
| Cart & Checkout Portal (Auth and Role: customer, operator, admin)
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth', 'role:customer,operator,admin'])->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
    Route::put('/keranjang/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/sukses/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
});

/*
|--------------------------------------------------------------------------
| Customer Portal (Auth and Role: customer)
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/pesanan/{orderNumber}/pembayaran', [PaymentController::class, 'create'])->name('customer.payments.create');
    Route::post('/pesanan/{orderNumber}/pembayaran', [PaymentController::class, 'store'])->name('customer.payments.store');

    Route::get('/pesanan', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/pesanan/{orderNumber}', [OrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/pesanan/{orderNumber}/batal', [OrderController::class, 'cancel'])->name('customer.orders.cancel');
});

/*
|--------------------------------------------------------------------------
| Operator Portal (Auth and Role: operator,admin)
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth', 'role:operator,admin'])->group(function () {
    Route::get('/dashboard/operator', function () {
        // 1. Operational counts
        $waitingVerificationCount = \App\Models\Order::where('order_status', 'waiting_verification')->count();
        $processingCount = \App\Models\Order::whereIn('order_status', ['paid', 'processing', 'ready'])->count();
        $shippedCount = \App\Models\Order::where('order_status', 'shipped')->count();
        
        // 2. Low stock count (active products with stock <= 5)
        $lowStockCount = \App\Models\Product::where('stock', '<=', 5)
            ->where('is_active', true)
            ->count();

        // 3. Orders waiting verification (recent 5)
        $waitingOrders = \App\Models\Order::where('order_status', 'waiting_verification')
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 4. Orders in processing/paid/ready queue (recent 5)
        $processingOrders = \App\Models\Order::whereIn('order_status', ['paid', 'processing', 'ready'])
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 5. Low stock products list (active products with stock <= 5)
        $lowStockProducts = \App\Models\Product::where('stock', '<=', 5)
            ->where('is_active', true)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        return view('operator.dashboard', [
            'metrics' => [
                'waiting_verification' => $waitingVerificationCount,
                'processing' => $processingCount,
                'shipped' => $shippedCount,
                'low_stock_count' => $lowStockCount,
            ],
            'waiting_orders' => $waitingOrders,
            'processing_orders' => $processingOrders,
            'low_stock_products' => $lowStockProducts,
        ]);
    })->name('operator.dashboard');

    Route::get('/kelola/pesanan', [OperatorOrderController::class, 'index'])->name('operator.orders.index');
    Route::get('/kelola/pesanan/{orderNumber}', [OperatorOrderController::class, 'show'])->name('operator.orders.show');
    Route::put('/kelola/pesanan/{orderNumber}/status', [OperatorOrderController::class, 'updateStatus'])->name('operator.orders.update-status');
    Route::post('/kelola/pesanan/{orderNumber}/verifikasi-pembayaran', [OperatorOrderController::class, 'verifyPayment'])->name('operator.payments.verify');

    Route::get('/kelola/stok', [\App\Http\Controllers\Operator\StockController::class, 'index'])->name('operator.stock.index');
    Route::post('/kelola/stok/penyesuaian', [\App\Http\Controllers\Operator\StockController::class, 'adjust'])->name('operator.stock.adjust');
});

/*
|--------------------------------------------------------------------------
| Admin Portal (Auth and Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Admin Categories CRUD Resource
    Route::resource('/admin/kategori', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ])->parameters(['kategori' => 'kategori']);

    // Admin Products CRUD Resource
    Route::resource('/admin/produk', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ])->parameters(['produk' => 'produk']);

    // Admin Operator Management
    Route::get('/admin/operator', [\App\Http\Controllers\Admin\OperatorController::class, 'index'])->name('admin.operators.index');
    Route::post('/admin/operator', [\App\Http\Controllers\Admin\OperatorController::class, 'store'])->name('admin.operators.store');
    Route::put('/admin/operator/{user}', [\App\Http\Controllers\Admin\OperatorController::class, 'update'])->name('admin.operators.update');
    Route::post('/admin/operator/{user}/status', [\App\Http\Controllers\Admin\OperatorController::class, 'toggleStatus'])->name('admin.operators.toggle-status');

    // Admin Customer Management
    Route::get('/admin/pelanggan', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customers.index');
    Route::post('/admin/pelanggan/{user}/status', [\App\Http\Controllers\Admin\CustomerController::class, 'toggleStatus'])->name('admin.customers.toggle-status');

    Route::get('/admin/laporan', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/laporan/ekspor', [ReportController::class, 'export'])->name('admin.reports.export');
});

require __DIR__.'/auth.php';
