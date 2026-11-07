<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Database\Factories\SavingsGoalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'name', 'target_amount', 'target_date', 'description'])]
class SavingsGoal extends Model
{
    use BelongsToUser;

    /** @use HasFactory<SavingsGoalFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'target_date' => 'date',
        ];
    }

    /**
     * @return HasMany<Contribution, $this>
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    public function savedAmount(): float
    {
        return (float) ($this->contributions_sum_amount ?? $this->contributions()->sum('amount'));
    }

    public function progressPercent(): int
    {
        $target = (float) $this->target_amount;

        return $target > 0 ? (int) min(100, round($this->savedAmount() / $target * 100)) : 0;
    }
}
