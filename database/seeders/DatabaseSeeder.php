<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Contribution;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with isolated demo accounts.
     */
    public function run(): void
    {
        foreach ($this->accounts() as $account) {
            $user = User::query()->updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => $account['password'],
                    'email_verified_at' => now(),
                ],
            );

            foreach ($account['transactions'] as $transaction) {
                Transaction::query()->updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'date' => now()->subDays($transaction['offset'])->toDateString(),
                        'description' => $transaction['description'],
                    ],
                    [
                        'category' => $transaction['category'],
                        'type' => $transaction['type'],
                        'amount' => $transaction['amount'],
                        'method' => $transaction['method'],
                        'notes' => $transaction['notes'],
                    ],
                );
            }

            foreach ($account['budgets'] as $budget) {
                Budget::query()->updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'category' => $budget['category'],
                        'month' => now()->month,
                        'year' => now()->year,
                    ],
                    ['monthly_limit' => $budget['monthly_limit']],
                );
            }

            foreach ($account['goals'] as $goal) {
                $savingsGoal = SavingsGoal::query()->updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $goal['name'],
                    ],
                    [
                        'target_amount' => $goal['target_amount'],
                        'target_date' => now()->endOfYear()->toDateString(),
                        'description' => $goal['description'],
                    ],
                );

                foreach ($goal['contributions'] as $contribution) {
                    $savedContribution = Contribution::query()->updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'savings_goal_id' => $savingsGoal->id,
                            'contributed_on' => now()->subDays($contribution['offset'])->toDateString(),
                            'amount' => $contribution['amount'],
                        ],
                        [
                            'source' => $contribution['source'],
                            'notes' => $contribution['notes'],
                        ],
                    );

                    $savedContribution->syncTransaction();
                }
            }
        }
    }

    /**
     * @return list<array{
     *     email: string,
     *     name: string,
     *     password: string,
     *     transactions: list<array{offset: int, description: string, category: string, type: string, amount: float, method: string, notes: string}>,
     *     budgets: list<array{category: string, monthly_limit: float}>,
     *     goals: list<array{name: string, target_amount: float, description: string, contributions: list<array{amount: float, offset: int, source: string, notes: string}>}>
     * }>
     */
    private function accounts(): array
    {
        return [
            [
                'email' => 'demo@pennywise.test',
                'name' => 'Kyrie Santos',
                'password' => 'password123',
                'transactions' => [
                    ['offset' => 0, 'description' => 'Groceries', 'category' => 'Food', 'type' => 'Expense', 'amount' => 1250.00, 'method' => 'Cash', 'notes' => 'Weekly grocery shopping'],
                    ['offset' => 1, 'description' => 'Salary', 'category' => 'Income', 'type' => 'Income', 'amount' => 15000.00, 'method' => 'Bank transfer', 'notes' => 'Monthly salary'],
                    ['offset' => 2, 'description' => 'Grab ride', 'category' => 'Transportation', 'type' => 'Expense', 'amount' => 320.00, 'method' => 'E-wallet', 'notes' => 'Ride to the office'],
                    ['offset' => 3, 'description' => 'Electricity', 'category' => 'Bills', 'type' => 'Expense', 'amount' => 2800.00, 'method' => 'Online banking', 'notes' => 'Monthly electric bill'],
                    ['offset' => 4, 'description' => 'Coffee', 'category' => 'Food', 'type' => 'Expense', 'amount' => 180.00, 'method' => 'Cash', 'notes' => 'Coffee with friends'],
                    ['offset' => 6, 'description' => 'Freelance work', 'category' => 'Income', 'type' => 'Income', 'amount' => 2500.00, 'method' => 'E-wallet', 'notes' => 'Design side project'],
                    ['offset' => 8, 'description' => 'Movie night', 'category' => 'Entertainment', 'type' => 'Expense', 'amount' => 450.00, 'method' => 'Cash', 'notes' => 'Cinema tickets'],
                    ['offset' => 10, 'description' => 'Internet', 'category' => 'Bills', 'type' => 'Expense', 'amount' => 1500.00, 'method' => 'Online banking', 'notes' => 'Home fiber plan'],
                    ['offset' => 12, 'description' => 'Shopping', 'category' => 'Shopping', 'type' => 'Expense', 'amount' => 1700.00, 'method' => 'Credit card', 'notes' => 'New shoes'],
                    ['offset' => 14, 'description' => 'Water', 'category' => 'Bills', 'type' => 'Expense', 'amount' => 600.00, 'method' => 'Cash', 'notes' => 'Monthly water bill'],
                    ['offset' => 16, 'description' => 'Bonus', 'category' => 'Income', 'type' => 'Income', 'amount' => 3000.00, 'method' => 'Bank transfer', 'notes' => 'Performance bonus'],
                    ['offset' => 18, 'description' => 'Pharmacy', 'category' => 'Health', 'type' => 'Expense', 'amount' => 720.00, 'method' => 'Cash', 'notes' => 'Vitamins and medicine'],
                    ['offset' => 20, 'description' => 'Bus fare', 'category' => 'Transportation', 'type' => 'Expense', 'amount' => 120.00, 'method' => 'Cash', 'notes' => 'Commute'],
                    ['offset' => 24, 'description' => 'Restaurant', 'category' => 'Food', 'type' => 'Expense', 'amount' => 980.00, 'method' => 'Credit card', 'notes' => 'Family dinner'],
                    ['offset' => 28, 'description' => 'Allowance', 'category' => 'Income', 'type' => 'Income', 'amount' => 2000.00, 'method' => 'Cash', 'notes' => 'Monthly allowance'],
                    ['offset' => 32, 'description' => 'Subscription', 'category' => 'Entertainment', 'type' => 'Expense', 'amount' => 549.00, 'method' => 'E-wallet', 'notes' => 'Streaming plan'],
                    ['offset' => 38, 'description' => 'Groceries', 'category' => 'Food', 'type' => 'Expense', 'amount' => 1100.00, 'method' => 'Cash', 'notes' => 'Weekly grocery shopping'],
                    ['offset' => 45, 'description' => 'Insurance', 'category' => 'Bills', 'type' => 'Expense', 'amount' => 1800.00, 'method' => 'Online banking', 'notes' => 'Health insurance premium'],
                ],
                'budgets' => [
                    ['category' => 'Food', 'monthly_limit' => 3000.00],
                    ['category' => 'Transportation', 'monthly_limit' => 1500.00],
                    ['category' => 'Shopping', 'monthly_limit' => 2000.00],
                    ['category' => 'Bills', 'monthly_limit' => 3500.00],
                ],
                'goals' => [
                    [
                        'name' => 'New Laptop',
                        'target_amount' => 15000.00,
                        'description' => 'Save up for a faster laptop for school and freelance work.',
                        'contributions' => [
                            ['amount' => 7500.00, 'offset' => 5, 'source' => 'Salary', 'notes' => 'Initial contribution'],
                        ],
                    ],
                    [
                        'name' => 'Travel Fund',
                        'target_amount' => 8000.00,
                        'description' => 'A well-deserved trip at the end of the year.',
                        'contributions' => [
                            ['amount' => 3200.00, 'offset' => 7, 'source' => 'Freelance work', 'notes' => 'Initial contribution'],
                        ],
                    ],
                    [
                        'name' => 'Emergency Fund',
                        'target_amount' => 10000.00,
                        'description' => 'A safety net for unexpected expenses.',
                        'contributions' => [
                            ['amount' => 5000.00, 'offset' => 9, 'source' => 'Salary', 'notes' => 'Initial contribution'],
                        ],
                    ],
                ],
            ],
            [
                'email' => 'dummy1@pennywise.test',
                'name' => 'Dummy One',
                'password' => 'password1',
                'transactions' => [
                    ['offset' => 1, 'description' => 'Monthly allowance', 'category' => 'Income', 'type' => 'Income', 'amount' => 3000.00, 'method' => 'Cash', 'notes' => 'Allowance from parents'],
                    ['offset' => 3, 'description' => 'Tuition fee', 'category' => 'Bills', 'type' => 'Expense', 'amount' => 4500.00, 'method' => 'Online banking', 'notes' => 'Partial tuition payment'],
                    ['offset' => 5, 'description' => 'School books', 'category' => 'Shopping', 'type' => 'Expense', 'amount' => 850.00, 'method' => 'Cash', 'notes' => 'Textbooks for the semester'],
                    ['offset' => 6, 'description' => 'Coffee', 'category' => 'Food', 'type' => 'Expense', 'amount' => 120.00, 'method' => 'E-wallet', 'notes' => 'Study session'],
                    ['offset' => 7, 'description' => 'Jeepney fare', 'category' => 'Transportation', 'type' => 'Expense', 'amount' => 60.00, 'method' => 'Cash', 'notes' => 'Commute to school'],
                    ['offset' => 8, 'description' => 'Lunch', 'category' => 'Food', 'type' => 'Expense', 'amount' => 180.00, 'method' => 'Cash', 'notes' => 'Cafeteria lunch'],
                    ['offset' => 10, 'description' => 'Mobile load', 'category' => 'Bills', 'type' => 'Expense', 'amount' => 300.00, 'method' => 'E-wallet', 'notes' => 'Monthly data plan'],
                    ['offset' => 12, 'description' => 'Part-time job', 'category' => 'Income', 'type' => 'Income', 'amount' => 2000.00, 'method' => 'Bank transfer', 'notes' => 'Weekend shift'],
                    ['offset' => 14, 'description' => 'Movie night', 'category' => 'Entertainment', 'type' => 'Expense', 'amount' => 350.00, 'method' => 'Cash', 'notes' => 'Movie with classmates'],
                    ['offset' => 16, 'description' => 'Groceries', 'category' => 'Food', 'type' => 'Expense', 'amount' => 900.00, 'method' => 'Cash', 'notes' => 'Dorm supplies'],
                ],
                'budgets' => [
                    ['category' => 'Food', 'monthly_limit' => 1500.00],
                    ['category' => 'Transportation', 'monthly_limit' => 600.00],
                    ['category' => 'Entertainment', 'monthly_limit' => 500.00],
                    ['category' => 'Shopping', 'monthly_limit' => 1000.00],
                ],
                'goals' => [
                    [
                        'name' => 'School Laptop',
                        'target_amount' => 25000.00,
                        'description' => 'Save up for a laptop for online classes.',
                        'contributions' => [
                            ['amount' => 4000.00, 'offset' => 4, 'source' => 'Allowance', 'notes' => 'Started from allowance savings'],
                        ],
                    ],
                    [
                        'name' => 'Emergency Fund',
                        'target_amount' => 5000.00,
                        'description' => 'Small buffer for unexpected school costs.',
                        'contributions' => [
                            ['amount' => 1200.00, 'offset' => 6, 'source' => 'Allowance', 'notes' => 'Initial contribution'],
                        ],
                    ],
                ],
            ],
            [
                'email' => 'dummy2@pennywise.test',
                'name' => 'Dummy Two',
                'password' => 'password2',
                'transactions' => [
                    ['offset' => 0, 'description' => 'Client payment', 'category' => 'Income', 'type' => 'Income', 'amount' => 12000.00, 'method' => 'Bank transfer', 'notes' => 'Website project milestone'],
                    ['offset' => 2, 'description' => 'Rent', 'category' => 'Bills', 'type' => 'Expense', 'amount' => 6000.00, 'method' => 'Online banking', 'notes' => 'Monthly apartment rent'],
                    ['offset' => 4, 'description' => 'Groceries', 'category' => 'Food', 'type' => 'Expense', 'amount' => 2200.00, 'method' => 'Credit card', 'notes' => 'Two weeks of groceries'],
                    ['offset' => 5, 'description' => 'Internet', 'category' => 'Bills', 'type' => 'Expense', 'amount' => 1700.00, 'method' => 'Online banking', 'notes' => 'Fiber connection'],
                    ['offset' => 7, 'description' => 'Grab ride', 'category' => 'Transportation', 'type' => 'Expense', 'amount' => 450.00, 'method' => 'E-wallet', 'notes' => 'Client meeting'],
                    ['offset' => 9, 'description' => 'Dinner out', 'category' => 'Food', 'type' => 'Expense', 'amount' => 850.00, 'method' => 'Credit card', 'notes' => 'Dinner with friends'],
                    ['offset' => 11, 'description' => 'Gym membership', 'category' => 'Health', 'type' => 'Expense', 'amount' => 1200.00, 'method' => 'Credit card', 'notes' => 'Monthly gym fee'],
                    ['offset' => 13, 'description' => 'Software subscription', 'category' => 'Entertainment', 'type' => 'Expense', 'amount' => 1100.00, 'method' => 'E-wallet', 'notes' => 'Design tools'],
                    ['offset' => 15, 'description' => 'Project bonus', 'category' => 'Income', 'type' => 'Income', 'amount' => 5000.00, 'method' => 'Bank transfer', 'notes' => 'Early delivery bonus'],
                    ['offset' => 18, 'description' => 'Flight booking', 'category' => 'Transportation', 'type' => 'Expense', 'amount' => 4500.00, 'method' => 'Credit card', 'notes' => 'Upcoming vacation'],
                    ['offset' => 20, 'description' => 'Coffee', 'category' => 'Food', 'type' => 'Expense', 'amount' => 240.00, 'method' => 'E-wallet', 'notes' => 'Coffee while working'],
                    ['offset' => 22, 'description' => 'Clinic visit', 'category' => 'Health', 'type' => 'Expense', 'amount' => 1500.00, 'method' => 'Cash', 'notes' => 'Checkup and medicine'],
                ],
                'budgets' => [
                    ['category' => 'Food', 'monthly_limit' => 5000.00],
                    ['category' => 'Transportation', 'monthly_limit' => 3000.00],
                    ['category' => 'Bills', 'monthly_limit' => 9000.00],
                    ['category' => 'Health', 'monthly_limit' => 2500.00],
                ],
                'goals' => [
                    [
                        'name' => 'Japan Trip',
                        'target_amount' => 60000.00,
                        'description' => 'Travel fund for a two-week trip.',
                        'contributions' => [
                            ['amount' => 18000.00, 'offset' => 3, 'source' => 'Freelance work', 'notes' => 'Initial contribution'],
                        ],
                    ],
                    [
                        'name' => 'Emergency Fund',
                        'target_amount' => 30000.00,
                        'description' => 'Six months of living expenses.',
                        'contributions' => [
                            ['amount' => 9000.00, 'offset' => 8, 'source' => 'Salary', 'notes' => 'Initial contribution'],
                        ],
                    ],
                    [
                        'name' => 'New Camera',
                        'target_amount' => 40000.00,
                        'description' => 'Camera upgrade for client work.',
                        'contributions' => [
                            ['amount' => 12500.00, 'offset' => 12, 'source' => 'Freelance work', 'notes' => 'Initial contribution'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
