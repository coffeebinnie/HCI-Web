<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\Contribution;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesRenderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        Transaction::factory()->income()->create([
            'user_id' => $this->user->id,
            'amount' => 15000,
            'date' => now(),
        ]);

        Transaction::factory()->expense()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'amount' => 1250,
            'date' => now(),
        ]);

        Budget::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'monthly_limit' => 3000,
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $goal = SavingsGoal::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Emergency Fund',
            'target_amount' => 10000,
        ]);

        Contribution::factory()->create([
            'user_id' => $this->user->id,
            'savings_goal_id' => $goal->id,
            'amount' => 5000,
            'contributed_on' => now(),
        ]);
    }

    public function test_dashboard_renders_with_database_totals(): void
    {
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('₱15,000.00')
            ->assertSee('₱1,250.00')
            ->assertSee('₱5,000.00');
    }

    public function test_reports_page_renders(): void
    {
        $this->get(route('reports'))
            ->assertOk()
            ->assertSee('NET BALANCE')
            ->assertSee('₱13,750.00');
    }

    public function test_savings_contribution_page_renders(): void
    {
        $this->get(route('savings.contribution'))
            ->assertOk()
            ->assertSee('Emergency Fund');
    }
}
