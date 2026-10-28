<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Http\Requests\UpdatePositionWeightsRequest;
use App\Repositories\Contracts\PositionRepositoryInterface;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    public function __construct(
        protected PositionRepositoryInterface $positionRepository
    ) {}

    public function index(): JsonResponse
    {
        $positions = $this->positionRepository->getActivePositions();

        return response()->json([
            'status' => 'success',
            'data' => $positions,
        ]);
    }

    public function store(StorePositionRequest $request): JsonResponse
    {
        $position = $this->positionRepository->create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Posisi/Jabatan berhasil dibuat.',
            'data' => $position,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $position = $this->positionRepository->getWithRulesAndCriteria($id);

        if (! $position) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jabatan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $position,
        ]);
    }

    public function update(UpdatePositionRequest $request, string $id): JsonResponse
    {
        $updated = $this->positionRepository->update($id, $request->validated());

        if (! $updated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui jabatan atau jabatan tidak ditemukan.',
            ], 404);
        }

        $position = $this->positionRepository->findById($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Jabatan berhasil diperbarui.',
            'data' => $position,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->positionRepository->delete($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jabatan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Jabatan berhasil dihapus.',
        ]);
    }

    public function updateWeights(UpdatePositionWeightsRequest $request, string $id): JsonResponse
    {
        $position = $this->positionRepository->findById($id);

        if (! $position) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jabatan tidak ditemukan.',
            ], 404);
        }

        $weightsMap = [];
        foreach ($request->input('weights', []) as $item) {
            $weightsMap[$item['criterion_id']] = (float) $item['weight'];
        }

        $this->positionRepository->syncCriteriaWeights($id, $weightsMap);

        return response()->json([
            'status' => 'success',
            'message' => 'Bobot kriteria jabatan berhasil disimpan.',
            'data' => $this->positionRepository->getWithRulesAndCriteria($id),
        ]);
    }
}
