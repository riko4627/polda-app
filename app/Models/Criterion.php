<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criterion extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'criteria';

    protected $fillable = [
        'code',
        'name',
        'type',
        'scale_min',
        'scale_max',
        'default_weight',
        'description',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scale_min' => 'float',
            'scale_max' => 'float',
            'default_weight' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function positionCriteria(): HasMany
    {
        return $this->hasMany(PositionCriterion::class, 'criterion_id');
    }

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'position_criteria', 'criterion_id', 'position_id')
            ->withPivot('id', 'weight')
            ->withTimestamps();
    }

    public function assessmentScores(): HasMany
    {
        return $this->hasMany(AssessmentScore::class, 'criterion_id');
    }
}
