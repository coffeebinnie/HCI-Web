<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Database\Factories\ContributionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'savings_goal_id', 'amount', 'contributed_on', 'source', 'notes'])]
class Contribution extends Model
{
    use BelongsToUser;

    /** @use HasFactory<ContributionFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    public const SOURCES = ['Allowance', 'Salary', 'Freelance work', 'Savings', 'Bank account', 'Cash', 'E-wallet'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'contributed_on' => 'date',
        ];
    }

    /**
     * @return BelongsTo<SavingsGoal, $this>
     */
    public function savingsGoal(): BelongsTo
    {
        return $this->belongsTo(SavingsGoal::class);
    }

    /**
     * @return HasOne<Transaction, $this>
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Create or refresh the transaction that represents this contribution.
     */
    public function syncTransaction(): Transaction
    {
        $attributes = [
            'user_id' => $this->user_id,
            'date' => $this->contributed_on,
            'description' => 'Savings contribution - '.($this->savingsGoal?->name ?? 'Savings goal'),
            'category' => 'Savings',
            'type' => 'Expense',
            'amount' => $this->amount,
            'method' => $this->paymentMethod(),
            'notes' => $this->notes,
        ];

        $transaction = $this->transaction()->first();

        if ($transaction) {
            $transaction->update($attributes);

            return $transaction;
        }

        return $this->transaction()->create($attributes);
    }

    private function paymentMethod(): ?string
    {
        return match ($this->source) {
            'Cash' => 'Cash',
            'E-wallet' => 'E-wallet',
            'Bank account', 'Salary' => 'Bank transfer',
            default => null,
        };
    }
}
