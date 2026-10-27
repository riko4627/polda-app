<?php

namespace App\Repositories\Contracts;

use App\Models\Criterion;
use Illuminate\Database\Eloquent\Collection;

interface CriterionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection<int, Criterion>
     */
    public function getActive(): Collection;

    public function findByCode(string $code): ?Criterion;
}
