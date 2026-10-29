<?php

namespace App\Services;

use App\DTOs\TopsisCandidateScore;
use App\DTOs\TopsisCriterionConfig;
use App\Models\TopsisRun;
use App\Repositories\Contracts\PersonnelRepositoryInterface;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Repositories\Contracts\TopsisRunRepositoryInterface;
use Carbon\Carbon;
use InvalidArgumentException;

class SPKRecommendationService
{
    public function __construct(
        protected PositionRepositoryInterface $positionRepository,
        protected PersonnelRepositoryInterface $personnelRepository,
        protected TopsisRunRepositoryInterface $topsisRunRepository,
        protected EligibilityFilterService $eligibilityFilterService,
        protected TopsisEngineService $topsisEngineService
    ) {}

    /**
     * Menjalankan seleksi bertahap (Screening Syarat Mutlak + Perangkingan TOPSIS).
     */
    public function run(
        string $positionId,
        int $periodYear,
        ?string $executedById = null,
        ?string $notes = null
    ): TopsisRun {
        $position = $this->positionRepository->getWithRulesAndCriteria($positionId);

        if (! $position) {
            throw new InvalidArgumentException('Data jabatan target tidak ditemukan.');
        }

        // Ambil semua personel aktif beserta profil lengkap
        $allPersonnel = $this->personnelRepository->getAllActive([
            'rank',
            'certifications',
            'careerHistories',
            'assessmentScores' => fn ($q) => $q->where('period_year', $periodYear),
        ]);

        $totalCandidates = $allPersonnel->count();
        $eligiblePersonnel = [];
        $ineligibleResults = [];

        // ==========================================
        // TAHAP 1: Rule-Based Screening (Hard Filter)
        // ==========================================
        foreach ($allPersonnel as $personnel) {
            $check = $this->eligibilityFilterService->evaluate($personnel, $position->rule);

            if ($check->isEligible) {
                $eligiblePersonnel[] = $personnel;
            } else {
                $ineligibleResults[] = [
                    'personnel_id' => $personnel->id,
                    'is_eligible' => false,
                    'ineligibility_reasons' => $check->reasons,
                    'd_plus' => null,
                    'd_minus' => null,
                    'preference_score' => null,
                    'rank_position' => null,
                    'matrix_data' => null,
                ];
            }
        }

        $eligibleCandidatesCount = count($eligiblePersonnel);
        $eligibleResults = [];
        $topsisOutput = null;

        // ==========================================
        // TAHAP 2: Soft Scoring TOPSIS (Jika ada yang lolos)
        // ==========================================
        if ($eligibleCandidatesCount > 0) {
            // Ambil kriteria dan bobot yang aktif pada jabatan ini
            $criteriaConfigs = [];
            foreach ($position->positionCriteria as $pc) {
                $criterion = $pc->criterion;
                if ($criterion && $criterion->is_active) {
                    $criteriaConfigs[] = new TopsisCriterionConfig(
                        criterionId: $criterion->id,
                        code: $criterion->code,
                        name: $criterion->name,
                        type: $criterion->type,
                        weight: (float) $pc->weight
                    );
                }
            }

            if (count($criteriaConfigs) === 0) {
                throw new InvalidArgumentException('Jabatan ini belum memiliki konfigurasi kriteria penilaian aktif.');
            }

            // Siapkan vektor nilai mentah kandidat untuk kriteria yang aktif
            $candidatesScores = [];
            foreach ($eligiblePersonnel as $p) {
                $scoreMap = [];
                $scoresGrouped = $p->assessmentScores->keyBy('criterion_id');

                foreach ($criteriaConfigs as $crit) {
                    $scoreModel = $scoresGrouped->get($crit->criterionId);
                    $scoreMap[$crit->criterionId] = $scoreModel ? (float) $scoreModel->score : 0.0;
                }

                $candidatesScores[] = new TopsisCandidateScore(
                    personnelId: $p->id,
                    scores: $scoreMap
                );
            }

            // Eksekusi kalkulasi matematis TOPSIS
            $topsisOutput = $this->topsisEngineService->calculate($candidatesScores, $criteriaConfigs);

            foreach ($topsisOutput->candidateResults as $resultItem) {
                $eligibleResults[] = [
                    'personnel_id' => $resultItem->personnelId,
                    'is_eligible' => true,
                    'ineligibility_reasons' => null,
                    'd_plus' => $resultItem->dPlus,
                    'd_minus' => $resultItem->dMinus,
                    'preference_score' => $resultItem->preferenceScore,
                    'rank_position' => $resultItem->rank,
                    'matrix_data' => [
                        'normalized_scores' => $resultItem->normalizedScores,
                        'weighted_scores' => $resultItem->weightedScores,
                    ],
                ];
            }
        }

        // Gabungkan seluruh data hasil
        $allResults = array_merge($eligibleResults, $ineligibleResults);

        $runData = [
            'position_id' => $position->id,
            'executed_by' => $executedById,
            'run_date' => Carbon::now(),
            'period_year' => $periodYear,
            'total_candidates' => $totalCandidates,
            'eligible_candidates' => $eligibleCandidatesCount,
            'status' => 'completed',
            'notes' => $notes,
        ];

        // Simpan ke database melalui repository
        $savedRun = $this->topsisRunRepository->createRun($runData, $allResults);

        return $this->topsisRunRepository->getRunWithResults($savedRun->id);
    }
}
