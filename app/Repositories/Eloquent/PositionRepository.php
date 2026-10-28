<?php

namespace App\Repositories\Eloquent;

use App\Models\Position;
use App\Models\PositionCriterion;
use App\Models\PositionRule;
use App\Repositories\Contracts\PositionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PositionRepository extends BaseRepository implements PositionRepositoryInterface
{
    public function __construct(Position $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, Position>
     */
    public function getActivePositions(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->with(['rule', 'criteria'])
            ->orderBy('title')
            ->get();
    }

    public function findByCode(string $code): ?Position
    {
        return $this->model->newQuery()
            ->where('code', $code)
            ->with(['rule', 'criteria'])
            ->first();
    }

    public function getWithRulesAndCriteria(string $id): ?Position
    {
        return $this->model->newQuery()
            ->with(['rule.minRank', 'rule.requiredRank', 'positionCriteria.criterion'])
            ->find($id);
    }

    /**
     * @param  array<string, float>  $criteriaWeights
     */
    public function syncCriteriaWeights(string $positionId, array $criteriaWeights): void
    {
        DB::transaction(function () use ($positionId, $criteriaWeights): void {
            PositionCriterion::query()->where('position_id', $positionId)->delete();

            foreach ($criteriaWeights as $criterionId => $weight) {
                PositionCriterion::query()->create([
                    'position_id' => $positionId,
                    'criterion_id' => $criterionId,
                    'weight' => (float) $weight,
                ]);
            }
        });
    }

    /**
     * @param  array<string, mixed>  $ruleData
     */
    public function updateOrCreateRule(string $positionId, array $ruleData): void
    {
        PositionRule::query()->updateOrCreate(
            ['position_id' => $positionId],
            $ruleData
        );
    }
}
