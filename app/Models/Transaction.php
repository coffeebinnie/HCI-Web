<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'contribution_id', 'date', 'description', 'category', 'type', 'amount', 'method', 'notes'])]
class Transaction extends Model
{
    use BelongsToUser;

    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    public const TYPES = ['Expense', 'Income'];

    /**
     * @var list<string>
     */
    public const CATEGORIES = ['Food', 'Transportation', 'Shopping', 'Bills', 'Health', 'Entertainment', 'Savings', 'Income'];

    /**
     * @var list<string>
     */
    public const METHODS = ['Cash', 'E-wallet', 'Credit card', 'Bank transfer', 'Online banking'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<Contribution, $this>
     */
    public function contribution(): BelongsTo
    {
        return $this->belongsTo(Contribution::class);
    }
}
