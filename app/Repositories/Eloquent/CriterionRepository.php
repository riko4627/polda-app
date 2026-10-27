<?php

namespace App\Repositories\Eloquent;

use App\Models\Criterion;
use App\Repositories\Contracts\CriterionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CriterionRepository extends BaseRepository implements CriterionRepositoryInterface
{
    public function __construct(Criterion $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, Criterion>
     */
    public function getActive(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('code')
            ->get();
    }

    public function findByCode(string $code): ?Criterion
    {
        return $this->model->newQuery()
            ->where('code', $code)
            ->first();
    }
}
