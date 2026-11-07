<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBudgetRequest extends FormRequest
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
            'category' => [
                'required',
                'string',
                'max:100',
                Rule::unique('budgets')
                    ->where(fn ($query) => $query
                        ->where('user_id', $this->user()->id)
                        ->where('month', $this->integer('month'))
                        ->where('year', $this->integer('year'))),
            ],
            'monthly_limit' => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'between:2000,2100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category.unique' => 'A budget already exists for this category in the selected month.',
        ];
    }
}
