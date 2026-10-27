<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rank extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'code',
        'level_order',
        'category',
        'description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level_order' => 'integer',
        ];
    }

    public function personnel(): HasMany
    {
        return $this->hasMany(Personnel::class, 'rank_id');
    }

    public function minimumPositionRules(): HasMany
    {
        return $this->hasMany(PositionRule::class, 'min_rank_id');
    }

    public function requiredPositionRules(): HasMany
    {
        return $this->hasMany(PositionRule::class, 'required_rank_id');
    }
}
