<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardReportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $operator;
    private User $customer;
    private Category $category;
    private Product $normalProduct;
    private Product $lowStockProduct;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed users with respective roles using User factories
        $this->admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);
        $this->customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

        // Seed products using Product factories
        $this->category = Category::factory()->create();
        
        $this->normalProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 15,
            'is_active' => true,
            'price' => 100000,
        ]);

        $this->lowStockProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock' => 3, // Low stock <= 5
            'is_active' => true,
            'price' => 200000,
        ]);
    }

    /**
     * Test role-based authorization for admin dashboard and sales reports.
     */
    public function test_non_admins_cannot_access_dashboard_and_reports(): void
    {
        // 1. Guest redirect
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.reports.index'))->assertRedirect(route('login'));
        $this->get(route('admin.reports.export'))->assertRedirect(route('login'));

        // 2. Customer gets 403
        $this->actingAs($this->customer);
        $this->get(route('admin.dashboard'))->assertStatus(403);
        $this->get(route('admin.reports.index'))->assertStatus(403);
        $this->get(route('admin.reports.export'))->assertStatus(403);

        // 3. Operator gets 403
        $this->actingAs($this->operator);
        $this->get(route('admin.dashboard'))->assertStatus(403);
        $this->get(route('admin.reports.index'))->assertStatus(403);
        $this->get(route('admin.reports.export'))->assertStatus(403);
    }

    /**
     * Test that the Admin Dashboard correctly calculates and returns all metrics and trends.
     */
    public function test_admin_can_access_dashboard_with_correct_metrics(): void
    {
        // Create orders with specific states and dates using Order::create
        // Order 1: Completed, created today, total 150.000 (Successful sale)
        $order1 = Order::create([
            'order_number' => 'LJ-20260627-0001',
            'user_id' => $this->customer->id,
            'order_date' => Carbon::now(),
            'delivery_date' => Carbon::now()->addDays(2),
            'recipient_name' => 'Budi',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Alamat Pengiriman 1',
            'subtotal' => 120000,
            'delivery_fee' => 30000,
            'total' => 150000,
            'payment_status' => 'verified',
            'order_status' => 'completed',
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $this->normalProduct->id,
            'product_name' => $this->normalProduct->name,
            'unit_price' => 100000,
            'quantity' => 1,
            'subtotal' => 100000,
        ]);

        // Order 2: Pending payment, created today, total 50.000 (Unsuccessful/pending sale)
        $order2 = Order::create([
            'order_number' => 'LJ-20260627-0002',
            'user_id' => $this->customer->id,
            'order_date' => Carbon::now(),
            'delivery_date' => Carbon::now()->addDays(1),
            'recipient_name' => 'Andi',
            'recipient_phone' => '081234567891',
            'delivery_address' => 'Alamat Pengiriman 2',
            'subtotal' => 50000,
            'delivery_fee' => 0,
            'total' => 50000,
            'payment_status' => 'pending',
            'order_status' => 'pending_payment',
        ]);

        // Order 3: Waiting verification, created yesterday, total 100.000 (Unsuccessful/unverified sale)
        $order3 = Order::create([
            'order_number' => 'LJ-20260627-0003',
            'user_id' => $this->customer->id,
            'order_date' => Carbon::now()->subDay(),
            'delivery_date' => Carbon::now()->addDays(3),
            'recipient_name' => 'Cici',
            'recipient_phone' => '081234567892',
            'delivery_address' => 'Alamat Pengiriman 3',
            'subtotal' => 80000,
            'delivery_fee' => 20000,
            'total' => 100000,
            'payment_status' => 'waiting_verification',
            'order_status' => 'waiting_verification',
        ]);
        // Set backdated created_at manually
        $order3->created_at = Carbon::now()->subDay();
        $order3->save();

        // Hit the dashboard
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        
        // Assert Inertia page and props
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Dashboard')
            ->has('metrics')
            ->where('metrics.total_sales', 150000) // Only Order 1 is successful
            ->where('metrics.orders_today', 2) // Order 1 and Order 2
            ->where('metrics.status_counts.pending_payment', 1)
            ->where('metrics.status_counts.waiting_verification', 1)
            ->where('metrics.status_counts.completed', 1)
            ->has('low_stock_products', 1) // Only $this->lowStockProduct is <= 5
            ->where('low_stock_products.0.id', $this->lowStockProduct->id)
            ->has('recent_orders', 3)
            ->has('weekly_trend')
            ->has('monthly_trend')
        );
    }

    /**
     * Test that the Sales Report page filters transactions and calculates metrics correctly.
     */
    public function test_admin_can_filter_sales_report(): void
    {
        // Order 1: Completed, created yesterday, total 150.000
        $order1 = Order::create([
            'order_number' => 'LJ-20260627-0001',
            'user_id' => $this->customer->id,
            'order_date' => Carbon::now()->subDay(),
            'delivery_date' => Carbon::now()->addDays(2),
            'recipient_name' => 'Budi',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Alamat Pengiriman 1',
            'subtotal' => 120000,
            'delivery_fee' => 30000,
            'total' => 150000,
            'payment_status' => 'verified',
            'order_status' => 'completed',
        ]);
        $order1->created_at = Carbon::now()->subDay();
        $order1->save();

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $this->normalProduct->id,
            'product_name' => $this->normalProduct->name,
            'unit_price' => 100000,
            'quantity' => 1,
            'subtotal' => 100000,
        ]);

        // Order 2: Completed, created 5 days ago, total 200.000
        $order2 = Order::create([
            'order_number' => 'LJ-20260627-0002',
            'user_id' => $this->customer->id,
            'order_date' => Carbon::now()->subDays(5),
            'delivery_date' => Carbon::now()->addDays(1),
            'recipient_name' => 'Andi',
            'recipient_phone' => '081234567891',
            'delivery_address' => 'Alamat Pengiriman 2',
            'subtotal' => 200000,
            'delivery_fee' => 0,
            'total' => 200000,
            'payment_status' => 'verified',
            'order_status' => 'completed',
        ]);
        $order2->created_at = Carbon::now()->subDays(5);
        $order2->save();

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $this->lowStockProduct->id,
            'product_name' => $this->lowStockProduct->name,
            'unit_price' => 200000,
            'quantity' => 1,
            'subtotal' => 200000,
        ]);

        // Order 3: Cancelled, created 2 days ago, total 80.000
        $order3 = Order::create([
            'order_number' => 'LJ-20260627-0003',
            'user_id' => $this->customer->id,
            'order_date' => Carbon::now()->subDays(2),
            'delivery_date' => Carbon::now()->addDays(3),
            'recipient_name' => 'Cici',
            'recipient_phone' => '081234567892',
            'delivery_address' => 'Alamat Pengiriman 3',
            'subtotal' => 80000,
            'delivery_fee' => 0,
            'total' => 80000,
            'payment_status' => 'pending',
            'order_status' => 'cancelled',
        ]);
        $order3->created_at = Carbon::now()->subDays(2);
        $order3->save();

        $this->actingAs($this->admin);

        // 1. Query with date range excluding Order 2 (only last 3 days)
        $response = $this->get(route('admin.reports.index', [
            'start_date' => Carbon::today()->subDays(3)->format('Y-m-d'),
            'end_date' => Carbon::today()->format('Y-m-d'),
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/SalesReport')
            ->has('orders', 2) // Order 1 (1 day ago) and Order 3 (2 days ago)
            ->where('summary.total_transactions', 2)
            ->where('summary.total_revenue', 150000) // Only Order 1 is completed/realized
            ->where('summary.total_items_sold', 1)
            ->where('summary.best_sellers.0.product_name', $this->normalProduct->name)
        );

        // 2. Query with order_status filter (completed only)
        $response = $this->get(route('admin.reports.index', [
            'start_date' => Carbon::today()->subDays(10)->format('Y-m-d'),
            'end_date' => Carbon::today()->format('Y-m-d'),
            'order_status' => 'completed',
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('orders', 2) // Order 1 and Order 2 are completed
            ->where('summary.total_revenue', 350000) // 150.000 + 200.000
        );
    }

    /**
     * Test that the sales report export streams a valid CSV file.
     */
    public function test_admin_can_export_report_csv(): void
    {
        // Create a test order
        $order = Order::create([
            'order_number' => 'LJ-20260627-0001',
            'user_id' => $this->customer->id,
            'order_date' => Carbon::now(),
            'delivery_date' => Carbon::now()->addDays(2),
            'recipient_name' => 'Budi',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Alamat Pengiriman 1',
            'subtotal' => 120000,
            'delivery_fee' => 30000,
            'total' => 150000,
            'payment_status' => 'verified',
            'order_status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.export', [
            'start_date' => Carbon::today()->subDays(1)->format('Y-m-d'),
            'end_date' => Carbon::today()->format('Y-m-d'),
        ]));

        $response->assertStatus(200);
        
        // Assert file download headers
        $response->assertHeader('Content-type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=Laporan_Penjualan_' . Carbon::today()->subDays(1)->format('Y-m-d') . '_s_d_' . Carbon::today()->format('Y-m-d') . '.csv');
        
        // Assert CSV contains order number and customer name
        $content = $response->streamedContent();
        $this->assertStringContainsString('Nomor Pesanan', $content);
        $this->assertStringContainsString($order->order_number, $content);
        $this->assertStringContainsString($this->customer->name, $content);
    }
}
