<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunTopsisRequest;
use App\Repositories\Contracts\TopsisRunRepositoryInterface;
use App\Services\SPKRecommendationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(
        protected SPKRecommendationService $spkService,
        protected TopsisRunRepositoryInterface $topsisRunRepository
    ) {}

    /**
     * Jalankan proses seleksi rekomendasi penempatan untuk jabatan target.
     */
    public function run(RunTopsisRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $userId = $request->user()?->id;

            $run = $this->spkService->run(
                positionId: $data['position_id'],
                periodYear: (int) $data['period_year'],
                executedById: $userId,
                notes: $data['notes'] ?? null
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Proses seleksi dan kalkulasi TOPSIS berhasil dijalankan.',
                'data' => $this->formatRunDetail($run),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Daftar riwayat eksekusi rekomendasi.
     */
    public function history(Request $request): JsonResponse
    {
        $limit = (int) $request->input('limit', 15);
        $runs = $this->topsisRunRepository->getLatestRuns($limit);

        return response()->json([
            'status' => 'success',
            'data' => $runs,
        ]);
    }

    /**
     * Detail hasil kalkulasi TOPSIS (3 Tab: Rekomendasi Lolos, Personel TMS, Matriks Matematis).
     */
    public function show(string $id): JsonResponse
    {
        $run = $this->topsisRunRepository->getRunWithResults($id);

        if (! $run) {
            return response()->json([
                'status' => 'error',
                'message' => 'Riwayat kalkulasi rekomendasi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->formatRunDetail($run),
        ]);
    }

    /**
     * Format data sesuai 3 Tab pada PRD Modul 3.
     */
    protected function formatRunDetail($run): array
    {
        $eligibleList = [];
        $ineligibleList = [];
        $mathematicalDetails = [];

        foreach ($run->results as $result) {
            $personnel = $result->personnel;
            $personnelSummary = [
                'id' => $personnel?->id,
                'nrp' => $personnel?->nrp,
                'name' => $personnel?->name,
                'rank' => $personnel?->rank?->code,
                'current_position' => $personnel?->current_position,
                'unit_name' => $personnel?->unit_name,
            ];

            if ($result->is_eligible) {
                $eligibleList[] = [
                    'rank' => $result->rank_position,
                    'personnel' => $personnelSummary,
                    'preference_score' => (float) $result->preference_score,
                    'd_plus' => (float) $result->d_plus,
                    'd_minus' => (float) $result->d_minus,
                ];

                $mathematicalDetails[] = [
                    'personnel' => $personnelSummary,
                    'rank' => $result->rank_position,
                    'd_plus' => (float) $result->d_plus,
                    'd_minus' => (float) $result->d_minus,
                    'preference_score' => (float) $result->preference_score,
                    'matrix_data' => $result->matrix_data,
                ];
            } else {
                $ineligibleList[] = [
                    'personnel' => $personnelSummary,
                    'reasons' => $result->ineligibility_reasons ?? [],
                ];
            }
        }

        // Urutkan kandidat lolos berdasarkan ranking
        usort($eligibleList, fn ($a, $b) => $a['rank'] <=> $b['rank']);
        usort($mathematicalDetails, fn ($a, $b) => $a['rank'] <=> $b['rank']);

        return [
            'run_info' => [
                'id' => $run->id,
                'position' => [
                    'id' => $run->position?->id,
                    'code' => $run->position?->code,
                    'title' => $run->position?->title,
                    'quota' => $run->position?->quota,
                ],
                'run_date' => $run->run_date,
                'period_year' => $run->period_year,
                'total_candidates' => $run->total_candidates,
                'eligible_candidates' => $run->eligible_candidates,
                'ineligible_candidates' => $run->total_candidates - $run->eligible_candidates,
                'status' => $run->status,
                'notes' => $run->notes,
                'executor' => $run->executor?->name ?? 'Sistem SDM',
            ],
            // Tab 1: Hasil Akhir Rekomendasi & Peringkat
            'tab_1_ranked_recommendations' => $eligibleList,
            // Tab 2: Personel TMS & Alasan Eliminasi
            'tab_2_ineligible_personnel' => $ineligibleList,
            // Tab 3: Transparansi Matriks Matematis (D+, D-, Normalized, Weighted)
            'tab_3_mathematical_matrices' => $mathematicalDetails,
        ];
    }
}
