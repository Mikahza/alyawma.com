<?php

namespace Database\Factories;

use App\Enums\FoodUnit;
use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Food>
 */
class FoodFactory extends Factory
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
            'name' => fake()->unique()->words(2, true),
            'reference_quantity' => 100,
            'reference_unit' => FoodUnit::Grams,
            'protein_grams' => fake()->randomFloat(1, 0, 60),
            'calories' => fake()->numberBetween(0, 900),
            'fibre_grams' => fake()->randomFloat(1, 0, 20),
            'fat_grams' => fake()->randomFloat(1, 0, 40),
            'carbohydrate_grams' => fake()->randomFloat(1, 0, 90),
        ];
    }

    /**
     * Indicate that the food belongs to the given user.
     */
    public function ownedBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
