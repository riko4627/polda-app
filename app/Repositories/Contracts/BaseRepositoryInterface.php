<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * @param  array<int, string>  $columns
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * @param  array<int, string>  $columns
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * @param  array<int, string>  $columns
     * @param  array<int, string>  $relations
     */
    public function findById(string $id, array $columns = ['*'], array $relations = []): ?Model;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Model;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(string $id, array $attributes): bool;

    public function delete(string $id): bool;
}
