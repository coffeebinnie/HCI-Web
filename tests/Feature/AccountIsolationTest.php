<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_transactions_are_isolated_between_accounts(): void
    {
        $account = User::factory()->create();
        $otherAccount = User::factory()->create();

        Transaction::factory()->create([
            'user_id' => $account->id,
            'description' => 'Account groceries',
        ]);

        Transaction::factory()->create([
            'user_id' => $otherAccount->id,
            'description' => 'Other account rent',
        ]);

        $this->actingAs($account)
            ->get(route('transactions'))
            ->assertOk()
            ->assertSee('Account groceries')
            ->assertDontSee('Other account rent');
    }

    public function test_a_user_cannot_delete_another_accounts_transaction(): void
    {
        $account = User::factory()->create();
        $otherAccount = User::factory()->create();

        $transaction = Transaction::factory()->create([
            'user_id' => $otherAccount->id,
        ]);

        $this->actingAs($account)
            ->delete(route('transactions.destroy', $transaction))
            ->assertNotFound();

        $this->assertModelExists($transaction);
    }

    public function test_the_seeded_dummy_accounts_have_distinct_transactions(): void
    {
        $this->seed();

        $dummyOne = User::query()->where('email', 'dummy1@pennywise.test')->firstOrFail();
        $dummyTwo = User::query()->where('email', 'dummy2@pennywise.test')->firstOrFail();

        $this->assertSame(12, $dummyOne->transactions()->count());
        $this->assertSame(15, $dummyTwo->transactions()->count());
        $this->assertSame(0, $dummyOne->transactions()->where('description', 'Client payment')->count());
        $this->assertSame(1, $dummyTwo->transactions()->where('description', 'Client payment')->count());
        $this->assertSame(2, $dummyOne->transactions()->where('category', 'Savings')->count());
        $this->assertSame(3, $dummyTwo->transactions()->where('category', 'Savings')->count());
    }
}
