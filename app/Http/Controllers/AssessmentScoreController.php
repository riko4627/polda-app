<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssessmentScoreRequest;
use App\Repositories\Contracts\PersonnelRepositoryInterface;
use Illuminate\Http\JsonResponse;

class AssessmentScoreController extends Controller
{
    public function __construct(
        protected PersonnelRepositoryInterface $personnelRepository
    ) {}

    public function store(StoreAssessmentScoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $assessmentScore = $this->personnelRepository->recordAssessmentScore(
            personnelId: $data['personnel_id'],
            criterionId: $data['criterion_id'],
            score: (float) $data['score'],
            periodYear: (int) $data['period_year'],
            notes: $data['notes'] ?? null
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Nilai asesmen personel berhasil disimpan.',
            'data' => $assessmentScore,
        ], 201);
    }
}
