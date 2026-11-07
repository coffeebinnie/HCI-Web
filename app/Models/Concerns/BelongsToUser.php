<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    /**
     * Scope the model to the signed-in account and assign ownership on create.
     */
    protected static function bootBelongsToUser(): void
    {
        static::creating(function ($model): void {
            if ($model->user_id === null && auth()->check()) {
                $model->user_id = auth()->id();
            }
        });

        static::addGlobalScope('owner', function (Builder $builder): void {
            if (auth()->check()) {
                $builder->where($builder->getModel()->getTable().'.user_id', auth()->id());
            }
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
