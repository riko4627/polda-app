<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\CriterionRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CriterionController extends Controller
{
    public function __construct(
        protected CriterionRepositoryInterface $criterionRepository
    ) {}

    public function index(): JsonResponse
    {
        $criteria = $this->criterionRepository->getActive();

        return response()->json([
            'status' => 'success',
            'data' => $criteria,
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $criterion = $this->criterionRepository->findById($id);

        if (! $criterion) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kriteria tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $criterion,
        ]);
    }
}
