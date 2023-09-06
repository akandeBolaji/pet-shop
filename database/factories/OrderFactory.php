<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
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
            'order_status_id' => OrderStatus::factory(),
            'payment_id' => Payment::factory(),
            'uuid' => (string) Str::uuid(),
            'products' => [
                [
                    'product' => (string) Str::uuid(),
                    'quantity' => $this->faker->numberBetween(1, 5),
                ]
            ],
            'address' => [
                'billing' => $this->faker->address(),
                'shipping' => $this->faker->address(),
            ],
            'delivery_fee' => $this->faker->randomFloat(2, 5, 50),
            'amount' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
