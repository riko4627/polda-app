<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Criterion;
use App\Repositories\Contracts\CriterionRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CriterionWebController extends Controller
{
    public function __construct(
        protected CriterionRepositoryInterface $criterionRepository
    ) {}

    public function index(): View
    {
        $criteria = Criterion::query()->orderBy('code')->get();

        return view('criteria.index', compact('criteria'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:criteria,code'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:benefit,cost'],
            'scale_min' => ['required', 'numeric'],
            'scale_max' => ['required', 'numeric', 'gt:scale_min'],
            'default_weight' => ['required', 'numeric', 'min:0', 'max:1'],
            'description' => ['nullable', 'string'],
        ]);

        $this->criterionRepository->create([
            'code' => strtoupper(trim($validated['code'])),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'scale_min' => (float) $validated['scale_min'],
            'scale_max' => (float) $validated['scale_max'],
            'default_weight' => (float) $validated['default_weight'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('web.criteria.index')
            ->with('success', "Kriteria penilaian baru '{$validated['name']}' berhasil ditambahkan.");
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:benefit,cost'],
            'scale_min' => ['required', 'numeric'],
            'scale_max' => ['required', 'numeric', 'gt:scale_min'],
            'default_weight' => ['required', 'numeric', 'min:0', 'max:1'],
            'description' => ['nullable', 'string'],
        ]);

        $this->criterionRepository->update($id, [
            'name' => $validated['name'],
            'type' => $validated['type'],
            'scale_min' => (float) $validated['scale_min'],
            'scale_max' => (float) $validated['scale_max'],
            'default_weight' => (float) $validated['default_weight'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('web.criteria.index')
            ->with('success', 'Data kriteria penilaian berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $criterion = $this->criterionRepository->findById($id);

        if (! $criterion) {
            abort(404, 'Data kriteria tidak ditemukan.');
        }

        $name = $criterion->name;
        $this->criterionRepository->delete($id);

        return redirect()->route('web.criteria.index')
            ->with('success', "Kriteria penilaian '{$name}' berhasil dihapus.");
    }
}
