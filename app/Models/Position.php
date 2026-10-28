<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Position extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'title',
        'unit_name',
        'quota',
        'description',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quota' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function rule(): HasOne
    {
        return $this->hasOne(PositionRule::class, 'position_id');
    }

    public function positionCriteria(): HasMany
    {
        return $this->hasMany(PositionCriterion::class, 'position_id');
    }

    public function criteria(): BelongsToMany
    {
        return $this->belongsToMany(Criterion::class, 'position_criteria', 'position_id', 'criterion_id')
            ->withPivot('id', 'weight')
            ->withTimestamps();
    }

    public function topsisRuns(): HasMany
    {
        return $this->hasMany(TopsisRun::class, 'position_id');
    }
}
