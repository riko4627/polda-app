@extends('layouts.master')

@section('title', 'Hasil Rekomendasi: ' . $position->title . ' - Sidang Wanjak')

@section('content')
<div class="space-y-6">
    <!-- Header & Breadcrumbs -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a 
                href="{{ route('web.recommendations.index') }}" 
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-600 shadow-sm hover:bg-slate-50"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="rounded bg-amber-500/20 px-2 py-0.5 font-mono text-[11px] font-bold text-amber-700">
                        {{ $position->code }}
                    </span>
                    <span class="text-xs text-slate-400">&bull;</span>
                    <span class="text-xs font-semibold text-slate-500">T.A. {{ $run->period_year }}</span>
                </div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">{{ $position->title }}</h2>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a 
                href="{{ route('web.recommendations.index') }}" 
                class="rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
            >
                Uji Jabatan Lain
            </a>
            <a 
                href="{{ route('web.recommendations.history') }}" 
                class="rounded-xl bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-slate-800"
            >
                Riwayat Sidang Wanjak
            </a>
        </div>
    </div>

    <!-- Alert Flash -->
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Metadata & Statistics Banner -->
    <div class="rounded-2xl border border-slate-800 bg-slate-950 p-6 text-white shadow-lg">
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-5">
            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Kuota Formasi</p>
                <p class="mt-1 text-2xl font-black text-amber-400">{{ $quota }} Kursi</p>
                <span class="text-[10px] text-slate-400">Kebutuhan Satker</span>
            </div>

            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Total Kandidat</p>
                <p class="mt-1 text-2xl font-black text-white">{{ $run->total_candidates }}</p>
                <span class="text-[10px] text-slate-400">Personel Dinilai</span>
            </div>

            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Memenuhi Syarat (MS)</p>
                <p class="mt-1 text-2xl font-black text-emerald-400">{{ $run->eligible_candidates }}</p>
                <span class="text-[10px] text-emerald-500/80 font-medium">Lolos Hard Filter</span>
            </div>

            <div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Gugur (TMS)</p>
                <p class="mt-1 text-2xl font-black text-rose-400">{{ $run->total_candidates - $run->eligible_candidates }}</p>
                <span class="text-[10px] text-rose-400/80 font-medium">Diskualifikasi Administrasi</span>
            </div>

            <div class="col-span-2 sm:col-span-4 lg:col-span-1 border-t border-slate-800 pt-3 lg:border-t-0 lg:pt-0">
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Tanggal Sidang</p>
                <p class="mt-1 text-sm font-bold text-white">{{ $run->run_date->format('d M Y, H:i') }} WIB</p>
                <span class="text-[10px] text-slate-400">Status: Selesai</span>
            </div>
        </div>
    </div>

    <!-- 3 Tab Navigation Buttons -->
    <div class="flex overflow-x-auto rounded-2xl border border-slate-200 bg-white p-1.5 shadow-sm">
        <button 
            type="button" 
            onclick="switchTab('tab-ranked')" 
            id="btn-tab-ranked" 
            class="tab-btn flex flex-1 items-center justify-center gap-2 rounded-xl py-3 px-4 text-xs font-bold transition bg-amber-500 text-slate-950 shadow-sm"
        >
            <span>🥇 Tab 1: Peringkat Rekomendasi</span>
            <span class="rounded-full bg-slate-950/15 px-2 py-0.5 text-[10px]">{{ count($eligibleList) }} Lolos</span>
        </button>

        <button 
            type="button" 
            onclick="switchTab('tab-tms')" 
            id="btn-tab-tms" 
            class="tab-btn flex flex-1 items-center justify-center gap-2 rounded-xl py-3 px-4 text-xs font-bold transition text-slate-600 hover:text-slate-900 hover:bg-slate-100"
        >
            <span>❌ Tab 2: Personel TMS</span>
            <span class="rounded-full bg-rose-100 text-rose-700 px-2 py-0.5 text-[10px]">{{ count($ineligibleList) }} Gugur</span>
        </button>

        <button 
            type="button" 
            onclick="switchTab('tab-matrix')" 
            id="btn-tab-matrix" 
            class="tab-btn flex flex-1 items-center justify-center gap-2 rounded-xl py-3 px-4 text-xs font-bold transition text-slate-600 hover:text-slate-900 hover:bg-slate-100"
        >
            <span>📊 Tab 3: Transparansi Matriks Matematis</span>
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px]">X, R, Y, D&plusmn;</span>
        </button>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 1: Hasil Rekomendasi & Peringkat Kuota Formasi       -->
    <!-- ======================================================== -->
    <div id="tab-ranked" class="tab-content space-y-4">
        @if (count($eligibleList) === 0)
            <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="mt-3 text-base font-bold text-slate-900">Tidak Ada Personel yang Memenuhi Syarat</h3>
                <p class="mt-1 text-xs text-slate-500 max-w-md mx-auto">
                    Seluruh kandidat gugur pada penyaringan syarat mutlak (*Hard Filter*). Silakan cek Tab 2 untuk melihat rincian alasan diskualifikasi.
                </p>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 bg-slate-50/75 px-6 py-4">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Daftar Rekomendasi Kandidat Terpilih</h3>
                            <p class="text-xs text-slate-500">Urutan prioritas berdasarkan skor preferensi kedekatan ideal TOPSIS ($V_i$)</p>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 font-bold text-amber-800">
                                <span>★</span> Peringkat 1 s.d. {{ $quota }} (Sesuai Kuota)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($eligibleList as $res)
                        @php
                            $isQuotaApproved = ($res->rank_position <= $quota);
                            $personnel = $res->personnel;
                        @endphp
                        <div class="p-6 transition hover:bg-slate-50/60 {{ $isQuotaApproved ? 'bg-amber-50/20' : '' }}">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <!-- Rank Number & Personnel Identity -->
                                <div class="flex items-start gap-4">
                                    <!-- Rank Badge -->
                                    <div class="flex flex-col items-center justify-center shrink-0">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl font-black text-lg shadow-md {{ $isQuotaApproved ? 'bg-gradient-to-tr from-amber-500 to-amber-400 text-slate-950 shadow-amber-500/20' : 'bg-slate-200 text-slate-700' }}">
                                            #{{ $res->rank_position }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('web.personnel.show', $personnel->id) }}" class="text-base font-bold text-slate-900 hover:text-amber-600">
                                                {{ $personnel->name }}
                                            </a>
                                            <span class="rounded border border-amber-300 bg-amber-50 px-2 py-0.5 text-xs font-bold text-amber-800">
                                                {{ $personnel->rank?->code }}
                                            </span>
                                            
                                            <!-- Quota Status Badge -->
                                            @if ($isQuotaApproved)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500 px-2.5 py-0.5 text-[11px] font-bold text-white shadow-sm">
                                                    <span>✓</span> Rekomendasi Utama (Terpilih)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-200 px-2.5 py-0.5 text-[11px] font-bold text-slate-700">
                                                    Cadangan Wanjak
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                            <span>NRP: <strong>{{ $personnel->nrp }}</strong></span>
                                            <span>&bull;</span>
                                            <span>Jabatan Sekarang: {{ $personnel->current_position ?? '-' }}</span>
                                            <span>&bull;</span>
                                            <span>Satker: {{ $personnel->unit_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- TOPSIS Score Metric -->
                                <div class="flex sm:flex-col items-end justify-between sm:justify-center border-t border-slate-100 pt-3 sm:border-0 sm:pt-0">
                                    <div class="text-right">
                                        <span class="text-[11px] uppercase font-bold tracking-wider text-slate-400">Skor Preferensi TOPSIS ($V_i$)</span>
                                        <div class="flex items-center justify-end gap-2">
                                            <span class="text-2xl font-black text-slate-900">{{ number_format($res->preference_score, 4) }}</span>
                                            <span class="text-xs font-bold text-amber-600">({{ round($res->preference_score * 100, 1) }}%)</span>
                                        </div>
                                    </div>

                                    <div class="mt-1 flex items-center gap-3 text-[11px] text-slate-400">
                                        <span>$D^+$: <strong class="text-slate-600 font-mono">{{ number_format($res->d_plus, 4) }}</strong></span>
                                        <span>&bull;</span>
                                        <span>$D^-$: <strong class="text-slate-600 font-mono">{{ number_format($res->d_minus, 4) }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- ======================================================== -->
    <!-- TAB 2: Personel TMS (Diskualifikasi Syarat Mutlak)       -->
    <!-- ======================================================== -->
    <div id="tab-tms" class="tab-content hidden space-y-4">
        @if (count($ineligibleList) === 0)
            <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mt-3 text-base font-bold text-slate-900">Semua Personel Lolos Syarat Mutlak</h3>
                <p class="mt-1 text-xs text-slate-500">Tidak ada personel yang tereliminasi pada tahap administrasi.</p>
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-base font-bold text-slate-900">Daftar Personel Tidak Memenuhi Syarat (TMS)</h3>
                    <p class="text-xs text-slate-500">Personel yang terdiskualifikasi pada <strong>Tahap 1 (Rule-Based Screening)</strong> beserta rincian alasan mutlak yang tidak terpenuhi</p>
                </div>

                <div class="space-y-4">
                    @foreach ($ineligibleList as $tms)
                        @php
                            $personnel = $tms->personnel;
                            $reasons = $tms->ineligibility_reasons ?? [];
                        @endphp
                        <div class="rounded-xl border border-rose-100 bg-rose-50/40 p-4 transition hover:border-rose-200">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-100 font-bold text-sm text-rose-700">
                                        TMS
                                    </div>
                                    <div>
                                        <a href="{{ route('web.personnel.show', $personnel->id) }}" class="font-bold text-slate-900 hover:text-rose-600 text-sm">
                                            {{ $personnel->name }}
                                        </a>
                                        <div class="flex items-center gap-2 text-xs text-slate-500">
                                            <span>NRP: {{ $personnel->nrp }}</span>
                                            <span>&bull;</span>
                                            <span>Pangkat: <strong class="text-slate-700">{{ $personnel->rank?->code }}</strong></span>
                                            <span>&bull;</span>
                                            <span>Satker: {{ $personnel->unit_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <span class="rounded bg-rose-100 px-2 py-0.5 text-[10px] font-bold text-rose-700 self-start">
                                    {{ count($reasons) }} Alasan TMS
                                </span>
                            </div>

                            <!-- Detail Rincian Alasan Diskualifikasi -->
                            <div class="mt-3 rounded-lg border border-rose-200/60 bg-white p-3 text-xs">
                                <p class="font-bold uppercase tracking-wider text-[10px] text-rose-600 mb-1.5">Penyebab Tidak Memenuhi Syarat:</p>
                                <ul class="list-disc pl-5 space-y-1 text-slate-700 leading-relaxed">
                                    @foreach ($reasons as $reason)
                                        <li>{{ $reason }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- ======================================================== -->
    <!-- TAB 3: Transparansi Matriks Matematis TOPSIS             -->
    <!-- ======================================================== -->
    <div id="tab-matrix" class="tab-content hidden space-y-6">
        <!-- Box Penjelasan DSS -->
        <div class="rounded-2xl border border-slate-800 bg-slate-950 p-6 text-white shadow-md">
            <h3 class="text-sm font-bold uppercase tracking-wider text-amber-400">Pertanggungjawaban Ilmiah Sidang Wanjak:</h3>
            <p class="mt-1 text-xs text-slate-300 leading-relaxed">
                Algoritma TOPSIS menghitung jarak terpendek dari <strong>Solusi Ideal Positif ($A^+$)</strong> dan jarak terjauh dari <strong>Solusi Ideal Negatif ($A^-$)</strong> menggunakan normalisasi vektor Euclidean:
                $r_{ij} = \frac{x_{ij}}{\sqrt{\sum x_{kj}^2}}$, terbobot $y_{ij} = w_j \cdot r_{ij}$, dan kedekatan preferensi $V_i = \frac{D_i^-}{D_i^+ + D_i^-}$.
            </p>
        </div>

        <!-- Matriks Detail Per Kandidat -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                <h3 class="text-sm font-bold text-slate-900">Tabel Transparansi Nilai Terbobot & Jarak Euclidean</h3>
                <p class="text-xs text-slate-500">Rincian nilai $y_{ij}$ per kriteria serta jarak ideal $D^+$ dan $D^-$</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="border-b border-slate-200 bg-slate-50/50 uppercase font-semibold text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Rank</th>
                            <th class="px-4 py-3">Nama Kandidat</th>
                            @foreach ($criteriaList as $crit)
                                <th class="px-4 py-3 text-center">
                                    {{ $crit['code'] }} ({{ round($crit['weight'] * 100) }}%)
                                    <span class="block text-[9px] font-normal text-slate-400">{{ $crit['type'] }}</span>
                                </th>
                            @endforeach
                            <th class="px-4 py-3 text-center font-mono">D&plus;</th>
                            <th class="px-4 py-3 text-center font-mono">D&minus;</th>
                            <th class="px-4 py-3 text-center font-mono font-bold text-slate-900">V (Skor)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($eligibleList as $res)
                            @php
                                $matrixData = $res->matrix_data ?? [];
                                $weightedScores = $matrixData['weighted_scores'] ?? [];
                            @endphp
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-3 font-bold text-slate-900">#{{ $res->rank_position }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-900">
                                    {{ $res->personnel?->name }}
                                    <span class="block text-[10px] text-slate-400">NRP: {{ $res->personnel?->nrp }}</span>
                                </td>
                                @foreach ($criteriaList as $crit)
                                    <td class="px-4 py-3 text-center font-mono">
                                        {{ isset($weightedScores[$crit['id']]) ? number_format($weightedScores[$crit['id']], 4) : '-' }}
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-center font-mono text-slate-600">{{ number_format($res->d_plus, 4) }}</td>
                                <td class="px-4 py-3 text-center font-mono text-slate-600">{{ number_format($res->d_minus, 4) }}</td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-amber-600">
                                    {{ number_format($res->preference_score, 4) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($criteriaList) + 5 }}" class="px-4 py-6 text-center text-slate-400">
                                    Tidak ada data matriks karena tidak ada kandidat lolos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tabId) {
        // Sembunyikan semua tab content
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });

        // Tampilkan tab yang dipilih
        const activeTab = document.getElementById(tabId);
        if (activeTab) {
            activeTab.classList.remove('hidden');
        }

        // Reset styling semua tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.className = 'tab-btn flex flex-1 items-center justify-center gap-2 rounded-xl py-3 px-4 text-xs font-bold transition text-slate-600 hover:text-slate-900 hover:bg-slate-100';
        });

        // Set active button style
        const activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.className = 'tab-btn flex flex-1 items-center justify-center gap-2 rounded-xl py-3 px-4 text-xs font-bold transition bg-amber-500 text-slate-950 shadow-sm';
        }
    }
</script>
@endpush
