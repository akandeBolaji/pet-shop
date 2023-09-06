<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        return [
            'uuid' => (string) Str::uuid(),
            'category_uuid' => Category::factory(),
            'title' => $this->faker->productName,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->paragraph,
            'metadata' => [
                'brand' => Brand::factory()->create()->uuid,
                'image' => null // Placeholder for now, as we haven't discussed the files table yet.
            ],
        ];
    }
}
