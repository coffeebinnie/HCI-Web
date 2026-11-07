<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContributionRequest;
use App\Http\Requests\UpdateContributionRequest;
use App\Models\Contribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ContributionController extends Controller
{
    /**
     * Store a newly created contribution.
     */
    public function store(StoreContributionRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $contribution = Contribution::create($request->validated());
            $contribution->syncTransaction();
        });

        return redirect()
            ->route('savings')
            ->with('status', 'Contribution recorded successfully.');
    }

    /**
     * Update the given contribution.
     */
    public function update(UpdateContributionRequest $request, Contribution $contribution): RedirectResponse
    {
        DB::transaction(function () use ($request, $contribution): void {
            $contribution->update($request->validated());
            $contribution->syncTransaction();
        });

        return redirect()
            ->route('savings')
            ->with('status', 'Contribution updated successfully.');
    }

    /**
     * Delete the given contribution.
     */
    public function destroy(Contribution $contribution): RedirectResponse
    {
        DB::transaction(function () use ($contribution): void {
            $contribution->transaction()->delete();
            $contribution->delete();
        });

        return redirect()
            ->route('savings')
            ->with('status', 'Contribution deleted successfully.');
    }
}
