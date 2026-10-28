<?php

namespace App\Repositories\Contracts;

use App\Models\TopsisRun;
use Illuminate\Database\Eloquent\Collection;

interface TopsisRunRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection<int, TopsisRun>
     */
    public function getLatestRuns(int $limit = 10): Collection;

    public function getRunWithResults(string $id): ?TopsisRun;

    /**
     * @param  array<string, mixed>  $runData
     * @param  array<int, array<string, mixed>>  $resultsData
     */
    public function createRun(array $runData, array $resultsData): TopsisRun;

    public function updateStatus(string $id, string $status): bool;
}
