<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonnelRequest;
use App\Http\Requests\UpdatePersonnelRequest;
use App\Models\Criterion;
use App\Models\Personnel;
use App\Repositories\Contracts\PersonnelRepositoryInterface;
use App\Repositories\Contracts\RankRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonnelWebController extends Controller
{
    public function __construct(
        protected PersonnelRepositoryInterface $personnelRepository,
        protected RankRepositoryInterface $rankRepository
    ) {}

    public function index(Request $request): View
    {
        $search = $request->input('search');
        $rankFilter = $request->input('rank_id');
        $trackFilter = $request->input('function_track');

        $query = Personnel::query()->with('rank');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nrp', 'like', "%{$search}%")
                    ->orWhere('current_position', 'like', "%{$search}%");
            });
        }

        if ($rankFilter) {
            $query->where('rank_id', $rankFilter);
        }

        if ($trackFilter) {
            $query->where('function_track', $trackFilter);
        }

        $personnelList = $query->orderBy('name')->paginate(10)->withQueryString();
        $ranks = $this->rankRepository->getOrdered();

        $functionTracks = [
            'Reskrim',
            'Lantas',
            'Intelkam',
            'Binmas',
            'Samapta',
            'SDM',
            'Propam',
            'Polairud',
            'Brimob',
        ];

        return view('personnel.index', compact(
            'personnelList',
            'ranks',
            'functionTracks',
            'search',
            'rankFilter',
            'trackFilter'
        ));
    }

    public function create(): View
    {
        $ranks = $this->rankRepository->getOrdered();
        $functionTracks = ['Reskrim', 'Lantas', 'Intelkam', 'Binmas', 'Samapta', 'SDM', 'Propam', 'Polairud', 'Brimob'];
        $educationLevels = ['SMA', 'D3', 'S1', 'S2', 'S3'];

        return view('personnel.create', compact('ranks', 'functionTracks', 'educationLevels'));
    }

    public function store(StorePersonnelRequest $request): RedirectResponse
    {
        $personnel = $this->personnelRepository->create($request->validated());

        return redirect()->route('web.personnel.show', $personnel->id)
            ->with('success', 'Data personel berhasil ditambahkan.');
    }

    public function show(string $id): View
    {
        $personnel = $this->personnelRepository->getWithFullProfile($id);

        if (! $personnel) {
            abort(404, 'Data personel tidak ditemukan.');
        }

        $criteria = Criterion::query()->where('is_active', true)->orderBy('code')->get();
        $periodYear = (int) request('period_year', 2026);

        // Map existing scores for the active period
        $scoresMap = $personnel->assessmentScores
            ->where('period_year', $periodYear)
            ->keyBy('criterion_id');

        return view('personnel.show', compact('personnel', 'criteria', 'scoresMap', 'periodYear'));
    }

    public function edit(string $id): View
    {
        $personnel = $this->personnelRepository->findById($id);

        if (! $personnel) {
            abort(404, 'Data personel tidak ditemukan.');
        }

        $ranks = $this->rankRepository->getOrdered();
        $functionTracks = ['Reskrim', 'Lantas', 'Intelkam', 'Binmas', 'Samapta', 'SDM', 'Propam', 'Polairud', 'Brimob'];
        $educationLevels = ['SMA', 'D3', 'S1', 'S2', 'S3'];

        return view('personnel.edit', compact('personnel', 'ranks', 'functionTracks', 'educationLevels'));
    }

    public function update(UpdatePersonnelRequest $request, string $id): RedirectResponse
    {
        $updated = $this->personnelRepository->update($id, $request->validated());

        if (! $updated) {
            return back()->with('error', 'Gagal memperbarui data personel.');
        }

        return redirect()->route('web.personnel.show', $id)
            ->with('success', 'Data personel berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $deleted = $this->personnelRepository->delete($id);

        if (! $deleted) {
            return back()->with('error', 'Gagal menghapus data personel.');
        }

        return redirect()->route('web.personnel.index')
            ->with('success', 'Data personel berhasil dihapus.');
    }

    public function storeScores(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'period_year' => ['required', 'integer', 'min:2000', 'max:2099'],
            'scores' => ['required', 'array'],
            'scores.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $periodYear = (int) $request->input('period_year');
        $scores = $request->input('scores', []);

        foreach ($scores as $criterionId => $scoreVal) {
            if ($scoreVal !== null && $scoreVal !== '') {
                $this->personnelRepository->recordAssessmentScore(
                    personnelId: $id,
                    criterionId: $criterionId,
                    score: (float) $scoreVal,
                    periodYear: $periodYear,
                    notes: "Input berkala tahun {$periodYear}"
                );
            }
        }

        return back()->with('success', "Nilai penilaian kinerja tahun {$periodYear} berhasil disimpan.");
    }

    public function storeHistory(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'position_title' => ['required', 'string', 'max:255'],
            'unit_name' => ['nullable', 'string', 'max:255'],
            'function_track' => ['nullable', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'sk_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->personnelRepository->addCareerHistory($id, $validated);

        return back()->with('success', 'Riwayat jabatan berhasil ditambahkan.');
    }

    public function storeCertification(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'certification_name' => ['required', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'certificate_number' => ['nullable', 'string', 'max:100'],
            'issue_date' => ['required', 'date'],
            'expiry_date' => ['nullable', 'date', 'after:issue_date'],
        ]);

        $this->personnelRepository->addCertification($id, $validated);

        return back()->with('success', 'Data sertifikasi/dikjur berhasil ditambahkan.');
    }
}
