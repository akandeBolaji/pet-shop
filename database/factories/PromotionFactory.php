<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'metadata' => json_encode([
                'valid_from' => $this->faker->date,
                'valid_to' => $this->faker->date,
                'image' => $this->faker->uuid,
            ]),
        ];
    }
}
