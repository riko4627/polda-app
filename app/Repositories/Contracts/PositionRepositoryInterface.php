<?php

namespace App\Repositories\Contracts;

use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;

interface PositionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection<int, Position>
     */
    public function getActivePositions(): Collection;

    public function findByCode(string $code): ?Position;

    public function getWithRulesAndCriteria(string $id): ?Position;

    /**
     * @param  array<string, float>  $criteriaWeights  [criterion_id => weight]
     */
    public function syncCriteriaWeights(string $positionId, array $criteriaWeights): void;

    /**
     * @param  array<string, mixed>  $ruleData
     */
    public function updateOrCreateRule(string $positionId, array $ruleData): void;
}
