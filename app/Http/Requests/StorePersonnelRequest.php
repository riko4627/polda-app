<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePersonnelRequest extends FormRequest
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
            'nrp' => ['required', 'string', 'max:20', 'unique:personnel,nrp'],
            'name' => ['required', 'string', 'max:255'],
            'rank_id' => ['required', 'uuid', 'exists:ranks,id'],
            'education_level' => ['required', 'string', 'in:SMA,D3,S1,S2,S3'],
            'education_major' => ['nullable', 'string', 'max:255'],
            'has_sespimmen' => ['boolean'],
            'current_position' => ['nullable', 'string', 'max:255'],
            'unit_name' => ['nullable', 'string', 'max:255'],
            'function_track' => ['required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,retired,suspended'],
        ];
    }
}
