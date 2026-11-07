<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
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
            'category' => fake()->randomElement(array_diff(Transaction::CATEGORIES, ['Income'])),
            'monthly_limit' => fake()->randomFloat(2, 1000, 10000),
            'month' => now()->month,
            'year' => now()->year,
        ];
    }
}
