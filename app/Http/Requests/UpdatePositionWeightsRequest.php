<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdatePositionWeightsRequest extends FormRequest
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
            'weights' => ['required', 'array', 'min:1'],
            'weights.*.criterion_id' => ['required', 'uuid', 'exists:criteria,id'],
            'weights.*.weight' => ['required', 'numeric', 'min:0', 'max:1'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $weights = $this->input('weights', []);
            if (! is_array($weights)) {
                return;
            }

            $total = 0.0;
            foreach ($weights as $item) {
                $total += (float) ($item['weight'] ?? 0);
            }

            // Must sum to 1.0 (allow margin of 0.0001 for floating point rounding)
            if (abs($total - 1.0) > 0.001) {
                $percent = round($total * 100, 2);
                $validator->errors()->add(
                    'weights',
                    "Total bobot kriteria harus berjumlah 100% (1.0). Total saat ini: {$percent}%."
                );
            }
        });
    }
}
