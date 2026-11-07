<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Display a filtered listing of transactions.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', Rule::in(Transaction::TYPES)],
            'category' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
        ]);

        $transactions = Transaction::query()
            ->when($filters['q'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->when($filters['type'] ?? null, fn ($query, string $type) => $query->where('type', $type))
            ->when($filters['category'] ?? null, fn ($query, string $category) => $query->where('category', $category))
            ->when($filters['from'] ?? null, fn ($query, string $date) => $query->whereDate('date', '>=', $date))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('transactions.index', [
            ...$this->shared(),
            'transactions' => $transactions,
            'categories' => Transaction::CATEGORIES,
            'types' => Transaction::TYPES,
            'methods' => Transaction::METHODS,
            'filters' => $filters,
        ]);
    }

    /**
     * Store a newly created transaction.
     */
    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        Transaction::create($request->validated());

        return redirect()
            ->route('transactions')
            ->with('status', 'Transaction added successfully.');
    }

    /**
     * Show the form for editing a transaction.
     */
    public function edit(Transaction $transaction): View
    {
        return view('transactions.edit', [
            ...$this->shared(),
            'transaction' => $transaction,
            'categories' => Transaction::CATEGORIES,
            'types' => Transaction::TYPES,
            'methods' => Transaction::METHODS,
        ]);
    }

    /**
     * Update the given transaction.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction->update($request->validated());

        return redirect()
            ->route('transactions')
            ->with('status', 'Transaction updated successfully.');
    }

    /**
     * Delete the given transaction.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return redirect()
            ->route('transactions')
            ->with('status', 'Transaction deleted successfully.');
    }
}
