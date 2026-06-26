<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed users with roles using our factories
        $this->admin = User::factory()->admin()->create();
        $this->customer = User::factory()->create(); // defaults to customer
    }

    public function test_guests_and_customers_cannot_access_category_crud_routes(): void
    {
        // 1. Guest test
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('login'));

        // 2. Customer test
        $response = $this->actingAs($this->customer)->get(route('admin.categories.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->customer)->post(route('admin.categories.store'), [
            'name' => 'New Category',
            'slug' => 'new-category',
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_can_access_category_crud_routes(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_category_with_valid_data(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Rose Bouquet',
            'slug' => 'rose-bouquet',
            'description' => 'Beautiful fresh red roses.',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Rose Bouquet',
            'slug' => 'rose-bouquet',
        ]);
    }

    public function test_admin_cannot_create_category_with_duplicate_slug(): void
    {
        Category::factory()->create(['slug' => 'duplicate-slug']);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Another Category',
            'slug' => 'duplicate-slug',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    public function test_admin_cannot_delete_category_with_products(): void
    {
        $category = Category::factory()->create();
        
        // Create a product linked to this category
        Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category->id));

        // Expect to be redirected back with an error flash message
        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        // Assert the category still exists in the database
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_can_delete_empty_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category->id));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
