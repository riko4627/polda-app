<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopsisRun extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'position_id',
        'executed_by',
        'run_date',
        'period_year',
        'total_candidates',
        'eligible_candidates',
        'status',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'run_date' => 'datetime',
            'period_year' => 'integer',
            'total_candidates' => 'integer',
            'eligible_candidates' => 'integer',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    public function results(): HasMany
    {
        return $this->hasMany(TopsisResult::class, 'topsis_run_id');
    }

    public function eligibleResults(): HasMany
    {
        return $this->hasMany(TopsisResult::class, 'topsis_run_id')
            ->where('is_eligible', true)
            ->orderBy('rank_position');
    }
}
