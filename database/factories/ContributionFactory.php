<?php

namespace Database\Factories;

use App\Models\Contribution;
use App\Models\SavingsGoal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contribution>
 */
class ContributionFactory extends Factory
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
            'savings_goal_id' => SavingsGoal::factory(),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'contributed_on' => fake()->dateTimeBetween('-3 months', 'now'),
            'source' => fake()->randomElement(Contribution::SOURCES),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
