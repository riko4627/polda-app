<?php

namespace App\Providers;

use App\Repositories\Contracts\CriterionRepositoryInterface;
use App\Repositories\Contracts\PersonnelRepositoryInterface;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Repositories\Contracts\RankRepositoryInterface;
use App\Repositories\Contracts\TopsisRunRepositoryInterface;
use App\Repositories\Eloquent\CriterionRepository;
use App\Repositories\Eloquent\PersonnelRepository;
use App\Repositories\Eloquent\PositionRepository;
use App\Repositories\Eloquent\RankRepository;
use App\Repositories\Eloquent\TopsisRunRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        RankRepositoryInterface::class => RankRepository::class,
        PositionRepositoryInterface::class => PositionRepository::class,
        CriterionRepositoryInterface::class => CriterionRepository::class,
        PersonnelRepositoryInterface::class => PersonnelRepository::class,
        TopsisRunRepositoryInterface::class => TopsisRunRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
