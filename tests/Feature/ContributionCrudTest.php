<?php

namespace Tests\Feature;

use App\Models\Contribution;
use App\Models\SavingsGoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContributionCrudTest extends TestCase
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

    public function test_a_contribution_is_recorded_as_a_transaction(): void
    {
        $goal = SavingsGoal::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'New Laptop',
        ]);

        $this->post(route('contributions.store'), [
            'savings_goal_id' => $goal->id,
            'amount' => 2000,
            'contributed_on' => '2026-04-24',
            'source' => 'Salary',
            'notes' => 'Monthly savings',
        ])->assertRedirect(route('savings'));

        $this->assertDatabaseHas('contributions', [
            'savings_goal_id' => $goal->id,
            'amount' => 2000,
            'source' => 'Salary',
        ]);

        $contribution = Contribution::query()
            ->where('savings_goal_id', $goal->id)
            ->firstOrFail();

        $transaction = $contribution->transaction()->firstOrFail();

        $this->assertSame('Savings contribution - New Laptop', $transaction->description);
        $this->assertSame('Savings', $transaction->category);
        $this->assertSame('Expense', $transaction->type);
        $this->assertSame('Bank transfer', $transaction->method);
        $this->assertEquals(2000, (float) $transaction->amount);
        $this->assertSame('2026-04-24', $transaction->date->toDateString());

        $this->get(route('transactions'))
            ->assertOk()
            ->assertSee('Savings contribution - New Laptop');
    }

    public function test_updating_a_contribution_updates_its_transaction(): void
    {
        $contribution = Contribution::factory()->create([
            'user_id' => $this->user->id,
            'savings_goal_id' => SavingsGoal::factory()->create(['user_id' => $this->user->id])->id,
            'amount' => 500,
            'source' => 'Cash',
        ]);

        $contribution->syncTransaction();

        $this->put(route('contributions.update', $contribution), [
            'savings_goal_id' => $contribution->savings_goal_id,
            'amount' => 1500,
            'contributed_on' => '2026-04-25',
            'source' => 'E-wallet',
            'notes' => null,
        ])->assertRedirect(route('savings'));

        $this->assertDatabaseHas('contributions', [
            'id' => $contribution->id,
            'amount' => 1500,
            'source' => 'E-wallet',
        ]);

        $transaction = $contribution->transaction()->firstOrFail();

        $this->assertEquals(1500, (float) $transaction->amount);
        $this->assertSame('2026-04-25', $transaction->date->toDateString());
        $this->assertSame('E-wallet', $transaction->method);
    }

    public function test_deleting_a_contribution_deletes_its_transaction(): void
    {
        $contribution = Contribution::factory()->create([
            'user_id' => $this->user->id,
            'savings_goal_id' => SavingsGoal::factory()->create(['user_id' => $this->user->id])->id,
        ]);

        $contribution->syncTransaction();

        $this->delete(route('contributions.destroy', $contribution))
            ->assertRedirect(route('savings'));

        $this->assertDatabaseMissing('transactions', ['contribution_id' => $contribution->id]);
        $this->assertDatabaseMissing('contributions', ['id' => $contribution->id]);
    }
}
