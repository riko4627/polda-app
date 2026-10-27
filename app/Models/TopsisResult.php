<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopsisResult extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'topsis_run_id',
        'personnel_id',
        'is_eligible',
        'ineligibility_reasons',
        'd_plus',
        'd_minus',
        'preference_score',
        'rank_position',
        'matrix_data',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_eligible' => 'boolean',
            'ineligibility_reasons' => 'array',
            'd_plus' => 'float',
            'd_minus' => 'float',
            'preference_score' => 'float',
            'rank_position' => 'integer',
            'matrix_data' => 'array',
        ];
    }

    public function topsisRun(): BelongsTo
    {
        return $this->belongsTo(TopsisRun::class, 'topsis_run_id');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }
}
