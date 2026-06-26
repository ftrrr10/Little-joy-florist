<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Hand Bouquet', 'Bloom Box', 'Flower Stand', 'Vase Arrangement', 'Orchid Plant'];
        $name = fake()->unique()->randomElement($categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(2),
            'is_active' => true,
        ];
    }
}
