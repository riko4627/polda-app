@extends('layouts.master')

@section('title', $personnel->name . ' - Profil Personel')

@section('content')
<div class="space-y-6">
    <!-- Header & Navigation -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a 
                href="{{ route('web.personnel.index') }}" 
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-600 shadow-sm hover:bg-slate-50"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Portofolio & Rekam Jejak Personel</h2>
                <p class="text-xs text-slate-500">Evaluasi profil individual, nilai kinerja berkala, dan verifikasi syarat mutlak.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('web.personnel.edit', $personnel->id) }}" 
                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
            >
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Profil</span>
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

    <!-- Profile Overview Card -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-slate-900 to-slate-800 text-2xl font-black text-amber-400 shadow-lg shadow-slate-900/20">
                    {{ substr($personnel->name, 0, 1) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-lg font-bold text-slate-900 sm:text-xl">{{ $personnel->name }}</h3>
                        <span class="rounded-lg border border-amber-300 bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                            {{ $personnel->rank?->code }} &bull; {{ $personnel->rank?->name }}
                        </span>
                        @if ($personnel->has_sespimmen)
                            <span class="rounded-lg bg-purple-100 px-2.5 py-0.5 text-xs font-bold text-purple-700">
                                Lulus Sespimmen
                            </span>
                        @endif
                    </div>
                    <div class="mt-1.5 flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-slate-500">
                        <span><strong>NRP:</strong> {{ $personnel->nrp }}</span>
                        <span>&bull;</span>
                        <span><strong>Jabatan:</strong> {{ $personnel->current_position ?? '-' }}</span>
                        <span>&bull;</span>
                        <span><strong>Satker:</strong> {{ $personnel->unit_name ?? '-' }}</span>
                        <span>&bull;</span>
                        <span><strong>Fungsi:</strong> {{ $personnel->function_track }}</span>
                        <span>&bull;</span>
                        <span><strong>Pendidikan:</strong> {{ $personnel->education_level }} {{ $personnel->education_major ? '(' . $personnel->education_major . ')' : '' }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-4 sm:border-0 sm:pt-0">
                <div class="text-right text-xs">
                    <p class="font-semibold text-slate-700">Status Kedinasan</p>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-emerald-700 font-bold">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Nilai Kinerja (TOPSIS) & Riwayat Karir -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Kolom Kiri: Form Input Nilai Kinerja (Asesmen C1 s.d. C6) -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Nilai Penilaian Kinerja (SMK & Asesmen)</h3>
                    <p class="text-xs text-slate-500">Parameter yang digunakan untuk kalkulasi matriks keputusan TOPSIS</p>
                </div>
                <!-- Filter Periode Tahun -->
                <form method="GET" action="{{ route('web.personnel.show', $personnel->id) }}" class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-slate-600">Periode:</label>
                    <select 
                        name="period_year" 
                        onchange="this.form.submit()" 
                        class="rounded-lg border border-slate-300 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-800 focus:outline-none"
                    >
                        @for ($y = 2026; $y >= 2023; $y--)
                            <option value="{{ $y }}" {{ $periodYear == $y ? 'selected' : '' }}>T.A. {{ $y }}</option>
                        @endfor
                    </select>
                </form>
            </div>

            <form method="POST" action="{{ route('web.personnel.scores', $personnel->id) }}" class="mt-5 space-y-4">
                @csrf
                <input type="hidden" name="period_year" value="{{ $periodYear }}">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ($criteria as $criterion)
                        @php
                            $currentScore = $scoresMap->get($criterion->id)?->score;
                        @endphp
                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4 transition hover:border-amber-300">
                            <div class="flex items-center justify-between">
                                <span class="rounded bg-slate-800 px-2 py-0.5 text-[11px] font-bold text-amber-400">
                                    {{ $criterion->code }}
                                </span>
                                <span class="text-[11px] font-semibold {{ $criterion->type === 'cost' ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ strtoupper($criterion->type) }}
                                </span>
                            </div>
                            <h4 class="mt-2 text-xs font-bold text-slate-800">{{ $criterion->name }}</h4>
                            <p class="mt-0.5 text-[11px] text-slate-500">{{ $criterion->description }}</p>

                            <div class="mt-3">
                                <label class="block text-[11px] font-semibold text-slate-600">Skor (Skala {{ (int)$criterion->scale_min }} - {{ (int)$criterion->scale_max }})</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="{{ $criterion->scale_min }}" 
                                    max="{{ $criterion->scale_max }}" 
                                    name="scores[{{ $criterion->id }}]" 
                                    value="{{ old("scores.{$criterion->id}", $currentScore) }}" 
                                    placeholder="0.00" 
                                    class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:outline-none"
                                >
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end pt-3">
                    <button 
                        type="submit" 
                        class="rounded-xl bg-amber-500 px-5 py-2 text-xs font-bold text-slate-950 shadow-md shadow-amber-500/20 transition hover:bg-amber-400"
                    >
                        Simpan Nilai Penilaian T.A. {{ $periodYear }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Portofolio Sertifikasi / Dikjur Khusus -->
        <div class="space-y-6">
            <!-- Portofolio Sertifikasi -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Sertifikasi & Dikjur</h3>
                        <p class="text-[11px] text-slate-500">Syarat mutlak keahlian (Penyidik, Bhabin, dll)</p>
                    </div>
                </div>

                <!-- List Sertifikasi -->
                <div class="mt-3 space-y-2">
                    @forelse ($personnel->certifications as $cert)
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 text-xs">
                            <p class="font-bold text-slate-900">{{ $cert->certification_name }}</p>
                            <p class="text-slate-500">{{ $cert->institution ?? 'Lemdiklat Polri' }} &bull; {{ $cert->issue_date->format('d/m/Y') }}</p>
                        </div>
                    @empty
                        <p class="py-2 text-center text-xs text-slate-400">Belum ada data sertifikasi.</p>
                    @endforelse
                </div>

                <!-- Form Tambah Sertifikasi -->
                <form method="POST" action="{{ route('web.personnel.certifications', $personnel->id) }}" class="mt-4 border-t border-slate-100 pt-3 space-y-2">
                    @csrf
                    <p class="text-xs font-bold text-slate-700">+ Tambah Dikjur / Sertifikasi</p>
                    <input 
                        type="text" 
                        name="certification_name" 
                        placeholder="Nama Dikjur/Sertifikasi..." 
                        required 
                        class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none"
                    >
                    <div class="grid grid-cols-2 gap-2">
                        <input 
                            type="text" 
                            name="institution" 
                            placeholder="Lembaga penerbit..." 
                            class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none"
                        >
                        <input 
                            type="date" 
                            name="issue_date" 
                            required 
                            class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none"
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="w-full rounded-lg bg-slate-900 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                    >
                        Simpan Sertifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat Jabatan Terdahulu (Full Width) -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Riwayat Penugasan & Jabatan Terdahulu</h3>
                <p class="text-xs text-slate-500">Rekam jejak kepemimpinan (Syarat mutlak Kabagdalpers: pernah Kapolres)</p>
            </div>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600">
                    <tr>
                        <th class="px-4 py-2.5">Jabatan Terdahulu</th>
                        <th class="px-4 py-2.5">Satuan Kerja / Wilayah</th>
                        <th class="px-4 py-2.5">Fungsi</th>
                        <th class="px-4 py-2.5">Masa Jabatan</th>
                        <th class="px-4 py-2.5">Nomor SK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($personnel->careerHistories as $history)
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $history->position_title }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $history->unit_name ?? '-' }}</td>
                            <td class="px-4 py-3"><span class="rounded bg-slate-100 px-2 py-0.5">{{ $history->function_track ?? '-' }}</span></td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $history->start_date->format('M Y') }} s.d. {{ $history->end_date ? $history->end_date->format('M Y') : 'Sekarang' }}
                            </td>
                            <td class="px-4 py-3 font-mono text-slate-500">{{ $history->sk_number ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-400">
                                Belum ada catatan riwayat jabatan terdahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Form Tambah Riwayat Jabatan -->
        <form method="POST" action="{{ route('web.personnel.histories', $personnel->id) }}" class="mt-5 border-t border-slate-100 pt-4">
            @csrf
            <h4 class="text-xs font-bold text-slate-800 mb-3">+ Tambah Rekam Jejak Jabatan Terdahulu</h4>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600">Nama Jabatan</label>
                    <input 
                        type="text" 
                        name="position_title" 
                        placeholder="Contoh: Kapolres Sleman" 
                        required 
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none"
                    >
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600">Kesatuan / Satker</label>
                    <input 
                        type="text" 
                        name="unit_name" 
                        placeholder="Contoh: Polres Sleman" 
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none"
                    >
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600">TMT Mulai</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        required 
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none"
                    >
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600">TMT Selesai</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none"
                    >
                </div>
            </div>
            <div class="mt-3 flex justify-end">
                <button 
                    type="submit" 
                    class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800"
                >
                    Simpan Riwayat Jabatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
