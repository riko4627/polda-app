<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonnelRequest;
use App\Http\Requests\UpdatePersonnelRequest;
use App\Repositories\Contracts\PersonnelRepositoryInterface;
use App\Repositories\Contracts\RankRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    public function __construct(
        protected PersonnelRepositoryInterface $personnelRepository,
        protected RankRepositoryInterface $rankRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $personnel = $this->personnelRepository->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $personnel,
        ]);
    }

    public function store(StorePersonnelRequest $request): JsonResponse
    {
        $personnel = $this->personnelRepository->create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Data personel berhasil ditambahkan.',
            'data' => $personnel,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $personnel = $this->personnelRepository->getWithFullProfile($id);

        if (! $personnel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data personel tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $personnel,
        ]);
    }

    public function update(UpdatePersonnelRequest $request, string $id): JsonResponse
    {
        $updated = $this->personnelRepository->update($id, $request->validated());

        if (! $updated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data personel atau personel tidak ditemukan.',
            ], 404);
        }

        $personnel = $this->personnelRepository->getWithFullProfile($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data personel berhasil diperbarui.',
            'data' => $personnel,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->personnelRepository->delete($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data personel tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data personel berhasil dihapus.',
        ]);
    }
}
