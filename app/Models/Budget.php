<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Carbon\Carbon;
use Database\Factories\BudgetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'category', 'monthly_limit', 'month', 'year'])]
class Budget extends Model
{
    use BelongsToUser;

    /** @use HasFactory<BudgetFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'monthly_limit' => 'decimal:2',
            'month' => 'integer',
            'year' => 'integer',
        ];
    }

    public function periodLabel(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F Y');
    }

    public function progressPercent(): int
    {
        $limit = (float) $this->monthly_limit;

        return $limit > 0 ? (int) min(100, round((float) $this->spent / $limit * 100)) : 0;
    }
}
