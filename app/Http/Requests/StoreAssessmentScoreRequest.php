<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentScoreRequest extends FormRequest
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
            'personnel_id' => ['required', 'uuid', 'exists:personnel,id'],
            'criterion_id' => ['required', 'uuid', 'exists:criteria,id'],
            'score' => ['required', 'numeric', 'min:0'],
            'period_year' => ['required', 'integer', 'min:2000', 'max:2099'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
