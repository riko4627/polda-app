<?php

namespace App\Repositories\Contracts;

use App\Models\AssessmentScore;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Collection;

interface PersonnelRepositoryInterface extends BaseRepositoryInterface
{
    public function findByNrp(string $nrp): ?Personnel;

    public function getWithFullProfile(string $id): ?Personnel;

    /**
     * @param  array<int, string>  $relations
     * @return Collection<int, Personnel>
     */
    public function getAllActive(array $relations = []): Collection;

    public function recordAssessmentScore(
        string $personnelId,
        string $criterionId,
        float $score,
        int $periodYear,
        ?string $notes = null
    ): AssessmentScore;

    /**
     * @param  array<string, mixed>  $historyData
     */
    public function addCareerHistory(string $personnelId, array $historyData): void;

    /**
     * @param  array<string, mixed>  $certificationData
     */
    public function addCertification(string $personnelId, array $certificationData): void;
}
