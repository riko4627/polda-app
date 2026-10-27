<?php

namespace App\Repositories\Eloquent;

use App\Models\TopsisResult;
use App\Models\TopsisRun;
use App\Repositories\Contracts\TopsisRunRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TopsisRunRepository extends BaseRepository implements TopsisRunRepositoryInterface
{
    public function __construct(TopsisRun $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, TopsisRun>
     */
    public function getLatestRuns(int $limit = 10): Collection
    {
        return $this->model->newQuery()
            ->with(['position', 'executor'])
            ->orderBy('run_date', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRunWithResults(string $id): ?TopsisRun
    {
        return $this->model->newQuery()
            ->with([
                'position.positionCriteria.criterion',
                'executor',
                'results.personnel.rank',
            ])
            ->find($id);
    }

    /**
     * @param  array<string, mixed>  $runData
     * @param  array<int, array<string, mixed>>  $resultsData
     */
    public function createRun(array $runData, array $resultsData): TopsisRun
    {
        return DB::transaction(function () use ($runData, $resultsData): TopsisRun {
            /** @var TopsisRun $run */
            $run = $this->model->create($runData);

            foreach ($resultsData as $resultItem) {
                $resultItem['topsis_run_id'] = $run->id;
                TopsisResult::query()->create($resultItem);
            }

            return $run;
        });
    }

    public function updateStatus(string $id, string $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }
}
