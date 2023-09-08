<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'is_admin' => $this->faker->boolean(20), // 20% chance of being true
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'avatar' => null,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'is_marketing' => $this->faker->boolean(50),
            'last_login_at' => $this->faker->dateTimeThisYear(),
        ];
    }


    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

        /**
     * Indicate that the user's is_marketing field is true.
     *
     * @return static
     */
    public function marketing()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_marketing' => true,
            ];
        });
    }

    /**
     * Indicate that the user is admin.
     *
     * @return static
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => true,
            ];
        });
    }

    /**
     * Indicate that the user's is_marketing field is true and is admin.
     *
     * @return static
     */
    public function admin_marketing()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_marketing' => true,
                'is_admin' => true,
            ];
        });
    }
}
