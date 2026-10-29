@extends('layouts.master')

@section('title', 'Dashboard Utama - SPK Penempatan Personel')

@section('content')
<div class="space-y-6">
    <!-- Hero / Welcome Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-6 sm:p-8 text-white shadow-xl border border-slate-700">
        <!-- Background Accents -->
        <div class="absolute -right-10 -bottom-10 h-60 w-60 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 right-1/4 h-32 w-32 rounded-full bg-blue-500/10 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-300 mb-3">
                <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l2.4 6.6 7 .6-5.3 4.6 1.6 6.9-5.7-3.5-5.7 3.5 1.6-6.9L2.6 9.2l7-.6L12 2z" />
                </svg>
                <span>Biro SDM Polda &bull; Bagbinkar & Wanjak</span>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                Sistem Pendukung Keputusan Penempatan Personel Kepolisian
            </h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-300 sm:text-base">
                Membantu penentuan penempatan personel pada jabatan strategis secara objektif, terukur, dan transparan melalui pendekatan <strong class="text-amber-300">Hybrid Architecture</strong>: Rule-Based Screening (Hard Filter) dan Algoritma TOPSIS dinamis per jabatan.
            </p>

            <div class="mt-5 flex flex-wrap gap-3">
                <a 
                    href="{{ url('/recommendations') }}" 
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-5 py-2.5 text-sm font-semibold text-slate-950 shadow-md shadow-amber-500/20 transition hover:from-amber-400 hover:to-amber-500"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Mulai Simulasi Seleksi Wanjak</span>
                </a>
                <a 
                    href="{{ url('/positions') }}" 
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800/80 px-4 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white"
                >
                    <span>Kelola 7 Profil Jabatan</span>
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1: Total Personel -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Personel Terdata</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $totalPersonnel ?? 0 }}</p>
                    <span class="text-[11px] text-emerald-600 font-medium">Status Aktif Dinas</span>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Master Jabatan -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Profil Jabatan</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $totalPositions ?? 0 }}</p>
                    <span class="text-[11px] text-amber-600 font-medium">Dengan Aturan Mutlak</span>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: Kriteria Dinamis -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kriteria Penilaian</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $totalCriteria ?? 0 }}</p>
                    <span class="text-[11px] text-indigo-600 font-medium">Benefit & Cost Terukur</span>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 4: Riwayat Simulasi -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Riwayat Seleksi</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $totalRuns ?? 0 }}</p>
                    <span class="text-[11px] text-slate-500 font-medium">Sidang Rekomendasi</span>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Column Section: 7 Profil Jabatan & Alur Hybrid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- 7 Profil Jabatan Target PRD -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">7 Profil Jabatan Target PRD</h3>
                    <p class="text-xs text-slate-500">Konfigurasi syarat mutlak administrasi dan kuota formasi</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                    7 Terdaftar
                </span>
            </div>

            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($positions ?? [] as $position)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 font-bold text-xs text-slate-700">
                                {{ substr($position->code, 0, 3) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900">{{ $position->title }}</h4>
                                <p class="text-xs text-slate-500">
                                    {{ $position->unit_name ?? 'Satker Terkait' }} &bull; Quota: <strong class="text-slate-800">{{ $position->quota }} Personel</strong>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if ($position->rule?->requires_sespimmen)
                                <span class="rounded bg-purple-50 px-2 py-0.5 text-[10px] font-semibold text-purple-700">
                                    Sespimmen
                                </span>
                            @endif
                            <span class="rounded bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700">
                                {{ $position->rule?->requiredRank?->code ?? ($position->rule?->minRank ? 'Min ' . $position->rule?->minRank?->code : 'Bintara') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-slate-400">Belum ada data jabatan.</p>
                @endforelse
            </div>
        </div>

        <!-- Alur Hybrid Architecture Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-900">Alur Hybrid Architecture</h3>
            <p class="mt-1 text-xs text-slate-500">Mekanisme kerja seleksi penempatan personel Polri</p>

            <div class="mt-5 space-y-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-slate-950">
                        1
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase">Tahap 1: Hard Filter</h4>
                        <p class="mt-0.5 text-xs text-slate-600 leading-relaxed">
                            Penyaringan syarat mutlak (Pangkat, Dikbang Sespimmen, Riwayat Jabatan, Sertifikasi). Personel tidak lolos langsung dinyatakan <strong>TMS</strong> beserta alasan diskualifikasi.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-amber-400">
                        2
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase">Tahap 2: TOPSIS Engine</h4>
                        <p class="mt-0.5 text-xs text-slate-600 leading-relaxed">
                            Kandidat yang Memenuhi Syarat (<strong>MS</strong>) dihitung matriks normalisasi, pembobotan spesifik jabatan, jarak ideal $D^+ / D^-$, hingga skor preferensi $V_i$.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white">
                        3
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase">Keputusan & Sidang Wanjak</h4>
                        <p class="mt-0.5 text-xs text-slate-600 leading-relaxed">
                            Penetapan urutan ranking rekomendasi prioritas tertinggi ($V_i \to 1$) untuk disahkan pimpinan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50/80 p-3.5 text-xs text-amber-900">
                <strong>Prinsip:</strong> <em>"The Right Man on The Right Place"</em> berbasis meritokrasi dan transparansi objektivitas.
            </div>
        </div>
    </div>
</div>
@endsection
