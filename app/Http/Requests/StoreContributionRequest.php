<?php

namespace App\Http\Requests;

use App\Models\Contribution;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContributionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'savings_goal_id' => [
                'required',
                'integer',
                Rule::exists('savings_goals', 'id')
                    ->where(fn ($query) => $query->where('user_id', $this->user()->id)),
            ],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'contributed_on' => ['required', 'date'],
            'source' => ['nullable', 'string', Rule::in(Contribution::SOURCES)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
