<?php

namespace App\Repositories\Contracts;

use App\Models\Rank;
use Illuminate\Database\Eloquent\Collection;

interface RankRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection<int, Rank>
     */
    public function getOrdered(): Collection;

    /**
     * @return Collection<int, Rank>
     */
    public function getByCategory(string $category): Collection;

    public function findByCode(string $code): ?Rank;
}
