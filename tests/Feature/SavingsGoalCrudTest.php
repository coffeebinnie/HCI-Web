<?php

namespace Tests\Feature;

use App\Models\Contribution;
use App\Models\SavingsGoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavingsGoalCrudTest extends TestCase
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

    public function test_savings_index_shows_goals_and_progress(): void
    {
        $goal = SavingsGoal::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'New Laptop',
            'target_amount' => 15000,
        ]);

        Contribution::factory()->create([
            'user_id' => $this->user->id,
            'savings_goal_id' => $goal->id,
            'amount' => 7500,
        ]);

        $this->get(route('savings'))
            ->assertOk()
            ->assertSee('New Laptop')
            ->assertSee('₱7,500.00')
            ->assertSee('50%');
    }

    public function test_savings_index_can_be_searched_by_name_or_description(): void
    {
        SavingsGoal::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'New Laptop',
            'description' => 'For work',
        ]);

        SavingsGoal::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Travel Fund',
            'description' => 'Year-end trip',
        ]);

        $response = $this->get(route('savings', ['q' => 'work']))
            ->assertOk()
            ->assertSee('New Laptop');

        $this->assertSame(1, substr_count($response->getContent(), 'data-goal-edit'));
    }

    public function test_a_savings_goal_can_be_created(): void
    {
        $this->post(route('savings-goals.store'), [
            'name' => 'Travel Fund',
            'target_amount' => 8000,
            'target_date' => '2026-12-31',
            'description' => 'Year-end trip',
        ])->assertRedirect(route('savings'));

        $this->assertDatabaseHas('savings_goals', [
            'name' => 'Travel Fund',
            'target_amount' => 8000,
        ]);
    }

    public function test_a_savings_goal_can_be_updated(): void
    {
        $goal = SavingsGoal::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Old Goal',
        ]);

        $this->put(route('savings-goals.update', $goal), [
            'name' => 'Updated Goal',
            'target_amount' => 20000,
            'target_date' => '2027-01-31',
            'description' => null,
        ])->assertRedirect(route('savings'));

        $this->assertDatabaseHas('savings_goals', [
            'id' => $goal->id,
            'name' => 'Updated Goal',
        ]);
    }

    public function test_a_savings_goal_can_be_deleted(): void
    {
        $goal = SavingsGoal::factory()->create(['user_id' => $this->user->id]);

        $this->delete(route('savings-goals.destroy', $goal))
            ->assertRedirect(route('savings'));

        $this->assertDatabaseMissing('savings_goals', ['id' => $goal->id]);
    }

    public function test_deleting_a_savings_goal_removes_its_contribution_transactions(): void
    {
        $goal = SavingsGoal::factory()->create(['user_id' => $this->user->id]);

        $contribution = Contribution::factory()->create([
            'user_id' => $this->user->id,
            'savings_goal_id' => $goal->id,
        ]);

        $contribution->syncTransaction();

        $this->delete(route('savings-goals.destroy', $goal))
            ->assertRedirect(route('savings'));

        $this->assertDatabaseMissing('contributions', ['id' => $contribution->id]);
        $this->assertDatabaseMissing('transactions', ['contribution_id' => $contribution->id]);
    }
}
