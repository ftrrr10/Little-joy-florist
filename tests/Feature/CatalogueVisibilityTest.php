<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogueVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private User $operator;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->category = Category::factory()->create();
        $this->operator = User::factory()->operator()->create();
        $this->customer = User::factory()->create();
    }

    public function test_public_can_view_active_products_in_catalogue(): void
    {
        $activeProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Active Rose Bouquet',
            'is_active' => true,
        ]);

        $response = $this->get(route('catalogue.index'));
        $response->assertStatus(200);
        $response->assertSee('Active Rose Bouquet');
    }

    public function test_public_cannot_view_inactive_products_in_catalogue(): void
    {
        $inactiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Draft Orchid Box',
            'is_active' => false,
        ]);

        $response = $this->get(route('catalogue.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Draft Orchid Box');
    }

    public function test_public_cannot_view_soft_deleted_products_in_catalogue(): void
    {
        $deletedProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Deleted Sunflower',
            'is_active' => true,
        ]);
        
        $deletedProduct->delete(); // Soft delete it

        $response = $this->get(route('catalogue.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Deleted Sunflower');
    }

    public function test_public_cannot_view_inactive_product_detail(): void
    {
        $inactiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'slug' => 'draft-orchid-box',
            'is_active' => false,
        ]);

        // 1. Guest test
        $response = $this->get(route('catalogue.show', 'draft-orchid-box'));
        $response->assertStatus(404);

        // 2. Customer test
        $response = $this->actingAs($this->customer)->get(route('catalogue.show', 'draft-orchid-box'));
        $response->assertStatus(404);
    }

    public function test_staff_can_view_inactive_product_detail_as_preview(): void
    {
        $inactiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'slug' => 'draft-orchid-box',
            'is_active' => false,
        ]);

        // Staff (operator) gets authorized to preview inactive drafts
        $response = $this->actingAs($this->operator)->get(route('catalogue.show', 'draft-orchid-box'));
        $response->assertStatus(200);
    }
}
