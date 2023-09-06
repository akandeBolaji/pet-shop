<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JwtToken>
 */
class JwtTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'unique_id' => $this->faker->uuid,
            'token_title' => $this->faker->word,
            'restrictions' => null,
            'permissions' => null,
            'expires_at' => $this->faker->dateTime,
            'last_used_at' => $this->faker->dateTime,
            'refreshed_at' => $this->faker->dateTime,
        ];
    }
}
