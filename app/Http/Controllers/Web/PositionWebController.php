<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Criterion;
use App\Models\Position;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Repositories\Contracts\RankRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionWebController extends Controller
{
    public function __construct(
        protected PositionRepositoryInterface $positionRepository,
        protected RankRepositoryInterface $rankRepository
    ) {}

    public function index(): View
    {
        $positions = Position::query()
            ->with(['rule.requiredRank', 'rule.minRank', 'positionCriteria.criterion'])
            ->orderBy('title')
            ->get();

        return view('positions.index', compact('positions'));
    }

    public function create(): View
    {
        $ranks = $this->rankRepository->getOrdered();
        $educationLevels = ['SMA', 'D3', 'S1', 'S2', 'S3'];
        $categories = ['tamtama', 'bintara', 'pama', 'pamen', 'pati'];

        return view('positions.create', compact('ranks', 'educationLevels', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:positions,code'],
            'title' => ['required', 'string', 'max:255'],
            'unit_name' => ['nullable', 'string', 'max:255'],
            'quota' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],

            // Rule validation
            'min_rank_id' => ['nullable', 'uuid', 'exists:ranks,id'],
            'required_rank_id' => ['nullable', 'uuid', 'exists:ranks,id'],
            'required_rank_category' => ['nullable', 'in:tamtama,bintara,pama,pamen,pati'],
            'min_education_level' => ['nullable', 'string', 'in:SMA,D3,S1,S2,S3'],
            'requires_sespimmen' => ['nullable', 'boolean'],
            'required_certification_keyword' => ['nullable', 'string', 'max:100'],
            'required_past_position_title' => ['nullable', 'string', 'max:100'],
            'required_function_track' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        /** @var Position $position */
        $position = $this->positionRepository->create([
            'code' => strtoupper(trim($validated['code'])),
            'title' => $validated['title'],
            'unit_name' => $request->input('unit_name'),
            'quota' => (int) $validated['quota'],
            'description' => $request->input('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $ruleData = [
            'min_rank_id' => $request->input('min_rank_id') ?: null,
            'required_rank_id' => $request->input('required_rank_id') ?: null,
            'required_rank_category' => $request->input('required_rank_category') ?: null,
            'min_education_level' => $request->input('min_education_level') ?: null,
            'requires_sespimmen' => $request->boolean('requires_sespimmen'),
            'required_certification_keyword' => $request->input('required_certification_keyword') ?: null,
            'required_past_position_title' => $request->input('required_past_position_title') ?: null,
            'required_function_track' => $request->input('required_function_track') ?: null,
            'notes' => $request->input('notes'),
        ];

        $this->positionRepository->updateOrCreateRule($position->id, $ruleData);

        // Pasangkan kriteria aktif dengan bobot bawaan
        $activeCriteria = Criterion::query()->where('is_active', true)->get();
        if ($activeCriteria->isNotEmpty()) {
            $weightsMap = [];
            foreach ($activeCriteria as $criterion) {
                $weightsMap[$criterion->id] = (float) $criterion->default_weight;
            }
            $this->positionRepository->syncCriteriaWeights($position->id, $weightsMap);
        }

        return redirect()->route('web.positions.index')
            ->with('success', "Formasi jabatan baru '{$position->title}' dan aturan syarat mutlak berhasil ditambahkan.");
    }

    public function show(string $id): View
    {
        $position = $this->positionRepository->getWithRulesAndCriteria($id);

        if (! $position) {
            abort(404, 'Data jabatan tidak ditemukan.');
        }

        return view('positions.show', compact('position'));
    }

    public function edit(string $id): View
    {
        $position = $this->positionRepository->getWithRulesAndCriteria($id);

        if (! $position) {
            abort(404, 'Data jabatan tidak ditemukan.');
        }

        $ranks = $this->rankRepository->getOrdered();
        $educationLevels = ['SMA', 'D3', 'S1', 'S2', 'S3'];
        $categories = ['tamtama', 'bintara', 'pama', 'pamen', 'pati'];

        return view('positions.edit', compact('position', 'ranks', 'educationLevels', 'categories'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'unit_name' => ['nullable', 'string', 'max:255'],
            'quota' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],

            // Rule validation
            'min_rank_id' => ['nullable', 'uuid', 'exists:ranks,id'],
            'required_rank_id' => ['nullable', 'uuid', 'exists:ranks,id'],
            'required_rank_category' => ['nullable', 'in:tamtama,bintara,pama,pamen,pati'],
            'min_education_level' => ['nullable', 'string', 'in:SMA,D3,S1,S2,S3'],
            'requires_sespimmen' => ['nullable', 'boolean'],
            'required_certification_keyword' => ['nullable', 'string', 'max:100'],
            'required_past_position_title' => ['nullable', 'string', 'max:100'],
            'required_function_track' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->positionRepository->update($id, [
            'title' => $validated['title'],
            'unit_name' => $request->input('unit_name'),
            'quota' => (int) $validated['quota'],
            'description' => $request->input('description'),
            'is_active' => (bool) ($request->has('is_active') ? $request->boolean('is_active') : true),
        ]);

        $ruleData = [
            'min_rank_id' => $request->input('min_rank_id') ?: null,
            'required_rank_id' => $request->input('required_rank_id') ?: null,
            'required_rank_category' => $request->input('required_rank_category') ?: null,
            'min_education_level' => $request->input('min_education_level') ?: null,
            'requires_sespimmen' => $request->boolean('requires_sespimmen'),
            'required_certification_keyword' => $request->input('required_certification_keyword') ?: null,
            'required_past_position_title' => $request->input('required_past_position_title') ?: null,
            'required_function_track' => $request->input('required_function_track') ?: null,
            'notes' => $request->input('notes'),
        ];

        $this->positionRepository->updateOrCreateRule($id, $ruleData);

        return redirect()->route('web.positions.show', $id)
            ->with('success', 'Konfigurasi jabatan dan aturan syarat mutlak berhasil diperbarui.');
    }

    public function weights(string $id): View
    {
        $position = $this->positionRepository->getWithRulesAndCriteria($id);

        if (! $position) {
            abort(404, 'Data jabatan tidak ditemukan.');
        }

        $allCriteria = Criterion::query()->where('is_active', true)->orderBy('code')->get();

        $currentWeightsMap = $position->positionCriteria->pluck('weight', 'criterion_id')->toArray();

        return view('positions.weights', compact('position', 'allCriteria', 'currentWeightsMap'));
    }

    public function updateWeights(Request $request, string $id): RedirectResponse
    {
        $rawWeights = $request->input('weights', []);

        $weightsMap = [];
        $totalWeight = 0.0;

        foreach ($rawWeights as $criterionId => $weightVal) {
            $val = (float) $weightVal;
            if ($val > 0.0) {
                $weightsMap[$criterionId] = $val;
                $totalWeight += $val;
            }
        }

        // Cek validasi persentase 100% (1.0) dengan toleransi rounding
        if (abs($totalWeight - 1.0) > 0.001) {
            $percentage = round($totalWeight * 100, 2);

            return back()->withInput()->with('error', "Total bobot kriteria wajib berjumlah 100% (1.0). Total bobot saat ini: {$percentage}%. Silakan sesuaikan kembali.");
        }

        $this->positionRepository->syncCriteriaWeights($id, $weightsMap);

        return redirect()->route('web.positions.show', $id)
            ->with('success', 'Konfigurasi bobot kriteria jabatan berhasil disimpan.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $position = $this->positionRepository->findById($id);

        if (! $position) {
            abort(404, 'Data jabatan tidak ditemukan.');
        }

        $title = $position->title;
        $this->positionRepository->delete($id);

        return redirect()->route('web.positions.index')
            ->with('success', "Jabatan '{$title}' berhasil dihapus dari sistem.");
    }
}
