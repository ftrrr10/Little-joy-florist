<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $flowerNames = [
            'Classic Red Roses Bouquet',
            'Pink Lily & Rose Bloom Box',
            'Sunny Sunflowers Standing Spray',
            'Elegant White Orchids in Vase',
            'Pastel Carnations Hand Bouquet',
            'Royal Purple Tulips Arrangement',
            'Rustic Wildflowers Bloom Box',
            'Grand Opening Congratulatory Stand',
            'Baby Breath Sweetheart Bouquet',
            'Luxury Hydrangea Collection',
            'White Lilies Sympathy Stand',
            'Crimson Gerbera Vase Display',
        ];

        $name = fake()->unique()->randomElement($flowerNames) . ' ' . Str::random(3);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 150000, 2000000), // Rp 150.000 - Rp 2.000.000
            'stock' => fake()->numberBetween(5, 30),
            'image_path' => null,
            'is_active' => true,
        ];
    }
}
