<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_budgets_index_shows_budget_and_spending(): void
    {
        Budget::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'monthly_limit' => 3000,
            'month' => now()->month,
            'year' => now()->year,
        ]);

        Transaction::factory()->expense()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'amount' => 1250,
            'date' => now(),
        ]);

        $this->get(route('budgets'))
            ->assertOk()
            ->assertSee('Food')
            ->assertSee('₱1,250.00');
    }

    public function test_budgets_index_can_be_searched_by_category(): void
    {
        Budget::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'month' => now()->month,
            'year' => now()->year,
        ]);

        Budget::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Transportation',
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response = $this->get(route('budgets', ['q' => 'Food']))
            ->assertOk()
            ->assertSee('Food');

        $this->assertSame(1, substr_count($response->getContent(), 'data-budget-edit'));
    }

    public function test_a_budget_can_be_created(): void
    {
        $this->post(route('budgets.store'), [
            'category' => 'Transportation',
            'monthly_limit' => 1500,
            'month' => 4,
            'year' => 2026,
        ])->assertRedirect(route('budgets'));

        $this->assertDatabaseHas('budgets', [
            'category' => 'Transportation',
            'month' => 4,
            'year' => 2026,
        ]);
    }

    public function test_a_budget_cannot_be_duplicated_for_the_same_period(): void
    {
        Budget::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'month' => 4,
            'year' => 2026,
        ]);

        $this->post(route('budgets.store'), [
            'category' => 'Food',
            'monthly_limit' => 5000,
            'month' => 4,
            'year' => 2026,
        ])->assertSessionHasErrors('category');
    }

    public function test_a_budget_can_be_updated(): void
    {
        $budget = Budget::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'monthly_limit' => 3000,
            'month' => 4,
            'year' => 2026,
        ]);

        $this->put(route('budgets.update', $budget), [
            'category' => 'Food',
            'monthly_limit' => 4500,
            'month' => 4,
            'year' => 2026,
        ])->assertRedirect(route('budgets'));

        $this->assertDatabaseHas('budgets', [
            'id' => $budget->id,
            'monthly_limit' => 4500,
        ]);
    }

    public function test_a_budget_can_be_deleted(): void
    {
        $budget = Budget::factory()->create(['user_id' => $this->user->id]);

        $this->delete(route('budgets.destroy', $budget))
            ->assertRedirect(route('budgets'));

        $this->assertDatabaseMissing('budgets', ['id' => $budget->id]);
    }
}
