<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('contribution_id')->nullable()->unique()->constrained()->cascadeOnDelete();
        });

        DB::table('contributions')
            ->orderBy('id')
            ->chunkById(100, function ($contributions): void {
                foreach ($contributions as $contribution) {
                    $goalName = DB::table('savings_goals')
                        ->where('id', $contribution->savings_goal_id)
                        ->value('name') ?? 'Savings goal';

                    DB::table('transactions')->updateOrInsert(
                        ['contribution_id' => $contribution->id],
                        [
                            'user_id' => $contribution->user_id,
                            'date' => $contribution->contributed_on,
                            'description' => 'Savings contribution - '.$goalName,
                            'category' => 'Savings',
                            'type' => 'Expense',
                            'amount' => $contribution->amount,
                            'method' => $this->transactionMethod($contribution->source),
                            'notes' => $contribution->notes,
                            'created_at' => $contribution->created_at,
                            'updated_at' => $contribution->updated_at,
                        ],
                    );
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique(['contribution_id']);
            $table->dropConstrainedForeignId('contribution_id');
        });
    }

    private function transactionMethod(?string $source): ?string
    {
        return match ($source) {
            'Cash' => 'Cash',
            'E-wallet' => 'E-wallet',
            'Bank account', 'Salary' => 'Bank transfer',
            default => null,
        };
    }
};
