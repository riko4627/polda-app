@extends('layouts.master')

@section('title', $position->title . ' - Detail Jabatan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a 
                href="{{ route('web.positions.index') }}" 
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-600 shadow-sm hover:bg-slate-50"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">{{ $position->title }}</h2>
                <p class="text-xs text-slate-500">Kode: <strong class="text-slate-800">{{ $position->code }}</strong> &bull; Satuan Kerja: {{ $position->unit_name ?? '-' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('web.positions.weights', $position->id) }}" 
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
            >
                <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span>Atur Bobot TOPSIS (100%)</span>
            </a>
            <a 
                href="{{ route('web.positions.edit', $position->id) }}" 
                class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-slate-800"
            >
                <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Ubah Aturan Syarat</span>
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Card 1: Aturan Syarat Mutlak (Hard Filter) -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">1. Aturan Syarat Mutlak (Hard Filter)</h3>
                    <p class="text-xs text-slate-500">Penyaringan awal administrasi sebelum pemeringkatan TOPSIS</p>
                </div>
                <span class="rounded bg-slate-900 px-2 py-0.5 text-[10px] font-bold text-amber-400">Tahap 1</span>
            </div>

            <div class="mt-4 space-y-3.5 text-xs">
                <!-- Pangkat Wajib / Minimal -->
                <div class="flex items-start justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500">Pangkat Wajib Mutlak:</span>
                    <span class="font-bold text-slate-800">
                        {{ $position->rule?->requiredRank ? $position->rule->requiredRank->code . ' (' . $position->rule->requiredRank->name . ')' : 'Tidak Ada (Bebas)' }}
                    </span>
                </div>

                <div class="flex items-start justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500">Pangkat Minimal:</span>
                    <span class="font-bold text-slate-800">
                        {{ $position->rule?->minRank ? 'Min. ' . $position->rule->minRank->code . ' (' . $position->rule->minRank->name . ')' : 'Tidak Ada' }}
                    </span>
                </div>

                <div class="flex items-start justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500">Golongan Kepangkatan:</span>
                    <span class="font-bold text-slate-800">
                        {{ $position->rule?->required_rank_category ? ucfirst($position->rule->required_rank_category) : 'Semua Golongan' }}
                    </span>
                </div>

                <!-- Dikbang Sespimmen -->
                <div class="flex items-start justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500">Pendidikan Dikbang:</span>
                    <span class="font-bold {{ $position->rule?->requires_sespimmen ? 'text-purple-700' : 'text-slate-700' }}">
                        {{ $position->rule?->requires_sespimmen ? 'Wajib Lulus Sespimmen Polri' : 'Tidak Dipersyaratkan' }}
                    </span>
                </div>

                <!-- Pendidikan Formal -->
                <div class="flex items-start justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500">Minimal Pendidikan:</span>
                    <span class="font-bold text-slate-800">
                        {{ $position->rule?->min_education_level ?? 'SMA / Sederajat' }}
                    </span>
                </div>

                <!-- Sertifikasi Khusus -->
                <div class="flex items-start justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500">Sertifikasi / Dikjur Wajib:</span>
                    <span class="font-bold text-slate-800">
                        {{ $position->rule?->required_certification_keyword ?? 'Tidak Ada' }}
                    </span>
                </div>

                <!-- Riwayat Jabatan Masa Lalu -->
                <div class="flex items-start justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500">Wajib Pernah Menjabat:</span>
                    <span class="font-bold text-slate-800">
                        {{ $position->rule?->required_past_position_title ?? 'Tidak Ada' }}
                    </span>
                </div>

                <!-- Rekam Jejak Fungsi -->
                <div class="flex items-start justify-between">
                    <span class="text-slate-500">Rekam Jejak Fungsi:</span>
                    <span class="font-bold text-slate-800">
                        {{ $position->rule?->required_function_track ?? 'Bebas / Semua Jalur' }}
                    </span>
                </div>
            </div>

            @if ($position->rule?->notes)
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50/70 p-3 text-xs text-amber-900">
                    <strong>Catatan Regulasi:</strong> {{ $position->rule->notes }}
                </div>
            @endif
        </div>

        <!-- Card 2: Pembobotan Kriteria (Soft Scoring TOPSIS) -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">2. Bobot Kriteria TOPSIS (Soft Scoring)</h3>
                    <p class="text-xs text-slate-500">Konfigurasi bobot preferensi penentu skor akhir kandidat lolos</p>
                </div>
                <span class="rounded bg-amber-500 px-2 py-0.5 text-[10px] font-bold text-slate-950">Tahap 2</span>
            </div>

            <!-- Total Weights Counter -->
            @php
                $sumWeights = $position->positionCriteria->sum('weight');
            @endphp
            <div class="mt-4 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs">
                <span class="font-semibold text-slate-700">Total Bobot Jabatan:</span>
                <span class="text-sm font-bold {{ abs($sumWeights - 1.0) < 0.001 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ round($sumWeights * 100, 1) }}% ({{ $sumWeights }})
                    @if (abs($sumWeights - 1.0) < 0.001)
                        &bull; VALID
                    @else
                        &bull; BELUM 100%
                    @endif
                </span>
            </div>

            <!-- List Criteria Weights -->
            <div class="mt-4 space-y-3">
                @foreach ($position->positionCriteria as $pc)
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 p-3 text-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="rounded bg-slate-800 px-2 py-0.5 font-bold font-mono text-[10px] text-amber-400">
                                {{ $pc->criterion?->code }}
                            </span>
                            <div>
                                <p class="font-bold text-slate-900">{{ $pc->criterion?->name }}</p>
                                <span class="text-[10px] uppercase font-semibold {{ $pc->criterion?->type === 'cost' ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ $pc->criterion?->type }}
                                </span>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-sm font-black text-slate-900">{{ round($pc->weight * 100, 1) }}%</span>
                            <span class="block text-[10px] text-slate-400">w = {{ $pc->weight }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
