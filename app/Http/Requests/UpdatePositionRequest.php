<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePositionRequest extends FormRequest
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
        $positionId = (string) $this->route('position');

        return [
            'code' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('positions', 'code')->ignore($positionId)],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'unit_name' => ['nullable', 'string', 'max:255'],
            'quota' => ['sometimes', 'required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
