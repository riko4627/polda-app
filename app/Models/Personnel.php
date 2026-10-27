<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Personnel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'personnel';

    protected $fillable = [
        'nrp',
        'name',
        'rank_id',
        'education_level',
        'education_major',
        'has_sespimmen',
        'current_position',
        'unit_name',
        'function_track',
        'phone',
        'email',
        'photo_path',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'has_sespimmen' => 'boolean',
        ];
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }

    public function careerHistories(): HasMany
    {
        return $this->hasMany(CareerHistory::class, 'personnel_id');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(PersonnelCertification::class, 'personnel_id');
    }

    public function assessmentScores(): HasMany
    {
        return $this->hasMany(AssessmentScore::class, 'personnel_id');
    }

    public function topsisResults(): HasMany
    {
        return $this->hasMany(TopsisResult::class, 'personnel_id');
    }
}
