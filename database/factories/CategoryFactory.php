<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->word;
        return [
            'uuid' => (string) Str::uuid(),
            'title' => $this->faker->word,
            'slug' => $this->faker->slug,
        ];
    }
}