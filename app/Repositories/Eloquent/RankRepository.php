<?php

namespace App\Repositories\Eloquent;

use App\Models\Rank;
use App\Repositories\Contracts\RankRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RankRepository extends BaseRepository implements RankRepositoryInterface
{
    public function __construct(Rank $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, Rank>
     */
    public function getOrdered(): Collection
    {
        return $this->model->newQuery()
            ->orderBy('level_order', 'asc')
            ->get();
    }

    /**
     * @return Collection<int, Rank>
     */
    public function getByCategory(string $category): Collection
    {
        return $this->model->newQuery()
            ->where('category', $category)
            ->orderBy('level_order', 'asc')
            ->get();
    }

    public function findByCode(string $code): ?Rank
    {
        return $this->model->newQuery()
            ->where('code', $code)
            ->first();
    }
}
