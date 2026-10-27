<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonnelRequest extends FormRequest
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
        $personnelId = (string) $this->route('personnel');

        return [
            'nrp' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('personnel', 'nrp')->ignore($personnelId)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'rank_id' => ['sometimes', 'required', 'uuid', 'exists:ranks,id'],
            'education_level' => ['sometimes', 'required', 'string', 'in:SMA,D3,S1,S2,S3'],
            'education_major' => ['nullable', 'string', 'max:255'],
            'has_sespimmen' => ['sometimes', 'boolean'],
            'current_position' => ['nullable', 'string', 'max:255'],
            'unit_name' => ['nullable', 'string', 'max:255'],
            'function_track' => ['sometimes', 'required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['sometimes', 'string', 'in:active,retired,suspended'],
        ];
    }
}
