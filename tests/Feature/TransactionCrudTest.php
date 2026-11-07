<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionCrudTest extends TestCase
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

    public function test_transactions_index_lists_records(): void
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'description' => 'Groceries',
        ]);

        $this->get(route('transactions'))
            ->assertOk()
            ->assertSee('Groceries')
            ->assertSee($transaction->category);
    }

    public function test_transactions_index_can_be_searched_and_filtered_by_start_date(): void
    {
        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'description' => 'Old groceries',
            'date' => '2026-04-01',
        ]);

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'description' => 'New groceries',
            'date' => '2026-04-20',
        ]);

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'description' => 'Monthly rent',
            'date' => '2026-04-20',
        ]);

        $this->get(route('transactions', [
            'q' => 'groceries',
            'from' => '2026-04-15',
        ]))
            ->assertOk()
            ->assertSee('New groceries')
            ->assertDontSee('Old groceries')
            ->assertDontSee('Monthly rent');
    }

    public function test_a_transaction_can_be_created(): void
    {
        $response = $this->post(route('transactions.store'), [
            'date' => '2026-04-20',
            'description' => 'Coffee',
            'category' => 'Food',
            'type' => 'Expense',
            'amount' => 150.50,
            'method' => 'Cash',
            'notes' => 'Coffee with friends',
        ]);

        $response->assertRedirect(route('transactions'));

        $this->assertDatabaseHas('transactions', [
            'description' => 'Coffee',
            'category' => 'Food',
            'type' => 'Expense',
            'method' => 'Cash',
        ]);
    }

    public function test_a_transaction_requires_valid_data(): void
    {
        $this->post(route('transactions.store'), [])
            ->assertSessionHasErrors(['date', 'description', 'category', 'type', 'amount']);
    }

    public function test_a_transaction_can_be_updated(): void
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'description' => 'Old description',
            'type' => 'Expense',
        ]);

        $this->put(route('transactions.update', $transaction), [
            'date' => '2026-04-21',
            'description' => 'New description',
            'category' => 'Bills',
            'type' => 'Expense',
            'amount' => 999.99,
            'method' => 'Online banking',
            'notes' => null,
        ])->assertRedirect(route('transactions'));

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'description' => 'New description',
            'category' => 'Bills',
        ]);
    }

    public function test_a_transaction_can_be_deleted(): void
    {
        $transaction = Transaction::factory()->create(['user_id' => $this->user->id]);

        $this->delete(route('transactions.destroy', $transaction))
            ->assertRedirect(route('transactions'));

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }
}
