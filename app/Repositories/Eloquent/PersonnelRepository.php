<?php

namespace App\Repositories\Eloquent;

use App\Models\AssessmentScore;
use App\Models\CareerHistory;
use App\Models\Personnel;
use App\Models\PersonnelCertification;
use App\Repositories\Contracts\PersonnelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PersonnelRepository extends BaseRepository implements PersonnelRepositoryInterface
{
    public function __construct(Personnel $model)
    {
        parent::__construct($model);
    }

    public function findByNrp(string $nrp): ?Personnel
    {
        return $this->model->newQuery()
            ->where('nrp', $nrp)
            ->with(['rank', 'certifications', 'careerHistories'])
            ->first();
    }

    public function getWithFullProfile(string $id): ?Personnel
    {
        return $this->model->newQuery()
            ->with([
                'rank',
                'certifications',
                'careerHistories' => fn ($query) => $query->orderBy('start_date', 'desc'),
                'assessmentScores.criterion',
            ])
            ->find($id);
    }

    /**
     * @param  array<int, string>  $relations
     * @return Collection<int, Personnel>
     */
    public function getAllActive(array $relations = []): Collection
    {
        return $this->model->newQuery()
            ->where('status', 'active')
            ->with($relations)
            ->get();
    }

    public function recordAssessmentScore(
        string $personnelId,
        string $criterionId,
        float $score,
        int $periodYear,
        ?string $notes = null
    ): AssessmentScore {
        return AssessmentScore::query()->updateOrCreate(
            [
                'personnel_id' => $personnelId,
                'criterion_id' => $criterionId,
                'period_year' => $periodYear,
            ],
            [
                'score' => $score,
                'notes' => $notes,
            ]
        );
    }

    /**
     * @param  array<string, mixed>  $historyData
     */
    public function addCareerHistory(string $personnelId, array $historyData): void
    {
        $historyData['personnel_id'] = $personnelId;
        CareerHistory::query()->create($historyData);
    }

    /**
     * @param  array<string, mixed>  $certificationData
     */
    public function addCertification(string $personnelId, array $certificationData): void
    {
        $certificationData['personnel_id'] = $personnelId;
        PersonnelCertification::query()->create($certificationData);
    }
}
