<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->admin()->create();
        $this->customer = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    public function test_guests_and_customers_cannot_access_product_crud_routes(): void
    {
        $response = $this->get(route('admin.products.index'));
        $response->assertRedirect(route('login'));

        $response = $this->actingAs($this->customer)->get(route('admin.products.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_product_with_valid_data_and_image(): void
    {
        // 1. Mock the public disk
        Storage::fake('public');

        // 2. Create a fake uploaded image file
        $file = UploadedFile::fake()->image('pink-lilies.jpg', 600, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Pink Lilies Arrangement',
            'slug' => 'pink-lilies-arrangement',
            'description' => 'Beautiful scented pink lilies.',
            'price' => 450000,
            'stock' => 15,
            'image' => $file,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        // 3. Assert the product exists in DB and has the image path
        $product = Product::where('slug', 'pink-lilies-arrangement')->firstOrFail();
        $this->assertNotNull($product->image_path);
        
        // 4. Assert the file was physically saved to our virtual public disk
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_admin_cannot_create_product_with_negative_price_or_stock(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Negative Price Bouquet',
            'slug' => 'negative-price-bouquet',
            'price' => -150000, // Invalid negative price
            'stock' => -5,      // Invalid negative stock
        ]);

        $response->assertSessionHasErrors(['price', 'stock']);
    }

    public function test_admin_can_soft_delete_product(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product->id));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        // Assert the product is soft-deleted (present in trashing but absent from active list)
        $this->assertSoftDeleted($product);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }
}
