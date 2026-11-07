<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['Expense', 'Expense', 'Expense', 'Income']);

        return [
            'user_id' => User::factory(),
            'date' => fake()->dateTimeBetween('-3 months', 'now'),
            'description' => fake()->sentence(3),
            'category' => $type === 'Income' ? 'Income' : fake()->randomElement(array_diff(Transaction::CATEGORIES, ['Income'])),
            'type' => $type,
            'amount' => fake()->randomFloat(2, 100, 5000),
            'method' => fake()->randomElement(Transaction::METHODS),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function income(): static
    {
        return $this->state(fn (): array => [
            'type' => 'Income',
            'category' => 'Income',
        ]);
    }

    public function expense(): static
    {
        return $this->state(fn (): array => [
            'type' => 'Expense',
            'category' => fake()->randomElement(array_diff(Transaction::CATEGORIES, ['Income'])),
        ]);
    }
}
