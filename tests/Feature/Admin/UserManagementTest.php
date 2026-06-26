<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $operator;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);

        $this->operator = User::factory()->create([
            'role' => User::ROLE_OPERATOR,
            'is_active' => true,
        ]);

        $this->customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'is_active' => true,
        ]);
    }

    /**
     * Test non-admins cannot access user management pages.
     */
    public function test_non_admins_cannot_access_user_management(): void
    {
        // 1. Customer try to access operators list
        $response = $this->actingAs($this->customer)->get(route('admin.operators.index'));
        $response->assertStatus(403);

        // 2. Operator try to access customers list
        $response = $this->actingAs($this->operator)->get(route('admin.customers.index'));
        $response->assertStatus(403);
    }

    /**
     * Test admin can view customers list with stats.
     */
    public function test_admin_can_view_customers_list(): void
    {
        // Create an order for customer
        $order = Order::create([
            'order_number' => 'LJ-20260627-0001',
            'user_id' => $this->customer->id,
            'order_date' => now(),
            'delivery_date' => now()->addDay(),
            'recipient_name' => 'Recipient Name',
            'recipient_phone' => '08123456789',
            'delivery_address' => 'Address',
            'subtotal' => 100000,
            'delivery_fee' => 25000,
            'total' => 125000,
            'payment_status' => 'verified',
            'order_status' => 'paid',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.customers.index'));
        
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/CustomerList')
            ->has('customers', 1)
            ->where('customers.0.id', $this->customer->id)
            ->where('customers.0.orders_count', 1)
            ->where('customers.0.total_spent', 125000)
        );
    }

    /**
     * Test admin can toggle customer status.
     */
    public function test_admin_can_toggle_customer_status(): void
    {
        $this->assertTrue($this->customer->is_active);

        // Toggle to inactive
        $response = $this->actingAs($this->admin)->post(route('admin.customers.toggle-status', $this->customer->id));
        $response->assertRedirect();
        
        $this->customer->refresh();
        $this->assertFalse($this->customer->is_active);

        // Toggle back to active
        $response = $this->actingAs($this->admin)->post(route('admin.customers.toggle-status', $this->customer->id));
        $response->assertRedirect();
        
        $this->customer->refresh();
        $this->assertTrue($this->customer->is_active);
    }

    /**
     * Test admin can view operators list.
     */
    public function test_admin_can_view_operators_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.operators.index'));
        
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/OperatorList')
            ->has('operators', 1)
            ->where('operators.0.id', $this->operator->id)
        );
    }

    /**
     * Test admin can create a new operator.
     */
    public function test_admin_can_create_operator(): void
    {
        $payload = [
            'name' => 'New Operator',
            'email' => 'new_op@littlejoy.com',
            'phone' => '08999999999',
            'password' => 'password123',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.operators.store'), $payload);
        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'name' => 'New Operator',
            'email' => 'new_op@littlejoy.com',
            'role' => User::ROLE_OPERATOR,
            'is_active' => true,
        ]);
    }

    /**
     * Test admin can edit operator details.
     */
    public function test_admin_can_edit_operator(): void
    {
        $payload = [
            'name' => 'Updated Name',
            'email' => 'updated_op@littlejoy.com',
            'phone' => '08777777777',
            'password' => 'newpassword123', // change password
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.operators.update', $this->operator->id), $payload);
        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $this->operator->id,
            'name' => 'Updated Name',
            'email' => 'updated_op@littlejoy.com',
            'phone' => '08777777777',
        ]);
    }

    /**
     * Test admin can toggle operator status.
     */
    public function test_admin_can_toggle_operator_status(): void
    {
        $this->assertTrue($this->operator->is_active);

        // Toggle to inactive
        $response = $this->actingAs($this->admin)->post(route('admin.operators.toggle-status', $this->operator->id));
        $response->assertRedirect();
        
        $this->operator->refresh();
        $this->assertFalse($this->operator->is_active);
    }
}
