<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\TopsisRun;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Repositories\Contracts\TopsisRunRepositoryInterface;
use App\Services\SPKRecommendationService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecommendationWebController extends Controller
{
    public function __construct(
        protected SPKRecommendationService $spkService,
        protected PositionRepositoryInterface $positionRepository,
        protected TopsisRunRepositoryInterface $topsisRunRepository
    ) {}

    /**
     * Halaman Utama Eksekusi Rekomendasi SPK.
     */
    public function index(): View
    {
        $positions = Position::query()
            ->where('is_active', true)
            ->with(['rule.requiredRank', 'rule.minRank', 'positionCriteria'])
            ->orderBy('title')
            ->get();

        $recentRuns = $this->topsisRunRepository->getLatestRuns(5);

        return view('recommendations.index', compact('positions', 'recentRuns'));
    }

    /**
     * Menjalankan kalkulasi seleksi bertahap (Hard Filter + TOPSIS).
     */
    public function run(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position_id' => ['required', 'uuid', 'exists:positions,id'],
            'period_year' => ['required', 'integer', 'min:2000', 'max:2099'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $run = $this->spkService->run(
                positionId: $validated['position_id'],
                periodYear: (int) $validated['period_year'],
                executedById: auth()->id(),
                notes: $validated['notes'] ?? 'Simulasi Sidang Wanjak Penempatan Personel'
            );

            return redirect()->route('web.recommendations.show', $run->id)
                ->with('success', 'Analisis rekomendasi SPK-TOPSIS berhasil dijalankan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal menjalankan analisis: '.$e->getMessage());
        }
    }

    /**
     * Menampilkan Hasil Rekomendasi dalam 3 Tab Interaktif (PRD Modul 3).
     */
    public function show(string $id): View
    {
        $run = $this->topsisRunRepository->getRunWithResults($id);

        if (! $run) {
            abort(404, 'Data riwayat kalkulasi rekomendasi tidak ditemukan.');
        }

        $position = $run->position;
        $quota = $position?->quota ?? 1;

        $eligibleList = [];
        $ineligibleList = [];

        foreach ($run->results as $result) {
            if ($result->is_eligible) {
                $eligibleList[] = $result;
            } else {
                $ineligibleList[] = $result;
            }
        }

        // Urutkan kandidat lolos berdasarkan ranking 1, 2, ...
        usort($eligibleList, fn ($a, $b) => $a->rank_position <=> $b->rank_position);

        // Kriteria aktif jabatan
        $criteriaList = $position?->positionCriteria->map(fn ($pc) => [
            'id' => $pc->criterion?->id,
            'code' => $pc->criterion?->code,
            'name' => $pc->criterion?->name,
            'type' => $pc->criterion?->type,
            'weight' => (float) $pc->weight,
        ]) ?? collect();

        return view('recommendations.show', compact(
            'run',
            'position',
            'quota',
            'eligibleList',
            'ineligibleList',
            'criteriaList'
        ));
    }

    /**
     * Riwayat Seluruh Sidang Keputusan Wanjak.
     */
    public function history(): View
    {
        $runs = TopsisRun::query()
            ->with(['position', 'executor'])
            ->orderBy('run_date', 'desc')
            ->paginate(15);

        return view('recommendations.history', compact('runs'));
    }
}
