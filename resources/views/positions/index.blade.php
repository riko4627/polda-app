@extends('layouts.master')

@section('title', 'Master Jabatan & Syarat Mutlak - Biro SDM Polda')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Manajemen Jabatan & Syarat Mutlak</h2>
            <p class="mt-1 text-sm text-slate-500">Kelola daftar jabatan, aturan eliminasi administrasi (*Hard Filter*), dan kuota formasi.</p>
        </div>
        <a 
            href="{{ route('web.positions.create') }}" 
            class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow-md shadow-amber-500/20 transition hover:bg-amber-400"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Tambah Jabatan Baru</span>
        </a>
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

    <!-- Position Cards Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($positions as $position)
            <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-amber-400 hover:shadow-md">
                <div>
                    <!-- Header Badges -->
                    <div class="flex items-center justify-between">
                        <span class="rounded bg-slate-900 px-2.5 py-1 text-xs font-mono font-bold text-amber-400">
                            {{ $position->code }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Quota: {{ $position->quota }} Kursi
                        </span>
                    </div>

                    <!-- Title & Unit -->
                    <h3 class="mt-4 text-base font-bold text-slate-900">{{ $position->title }}</h3>
                    <p class="text-xs text-slate-500">{{ $position->unit_name ?? 'Satuan Kerja Terkait' }}</p>

                    <!-- Rules Summary Box -->
                    <div class="mt-4 space-y-2 rounded-xl border border-slate-100 bg-slate-50/75 p-3.5 text-xs">
                        <p class="font-bold uppercase tracking-wider text-[10px] text-slate-400">Syarat Mutlak (Hard Filter):</p>
                        
                        <div class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-slate-700">
                                <strong>Pangkat:</strong> 
                                {{ $position->rule?->requiredRank?->code ? 'Wajib ' . $position->rule?->requiredRank?->code : ($position->rule?->minRank ? 'Min. ' . $position->rule?->minRank?->code : ($position->rule?->required_rank_category ? 'Gol. ' . ucfirst($position->rule?->required_rank_category) : 'Semua Pangkat')) }}
                            </span>
                        </div>

                        @if ($position->rule?->requires_sespimmen)
                            <div class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 text-purple-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-semibold text-purple-700">Wajib Lulus Dikbang Sespimmen</span>
                            </div>
                        @endif

                        @if ($position->rule?->required_past_position_title)
                            <div class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-slate-700">Pernah Menjabat: <strong>{{ $position->rule->required_past_position_title }}</strong></span>
                            </div>
                        @endif

                        @if ($position->rule?->required_certification_keyword)
                            <div class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-slate-700">Sertifikasi: <strong>{{ $position->rule->required_certification_keyword }}</strong></span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-6 border-t border-slate-100 pt-4 flex items-center justify-between gap-2">
                    <a 
                        href="{{ route('web.positions.show', $position->id) }}" 
                        class="text-xs font-semibold text-slate-700 hover:text-amber-600"
                    >
                        Lihat Detail &rarr;
                    </a>

                    <div class="flex items-center gap-1.5">
                        <a 
                            href="{{ route('web.positions.weights', $position->id) }}" 
                            class="rounded-lg border border-slate-300 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                            title="Atur Bobot TOPSIS (100%)"
                        >
                            Bobot (100%)
                        </a>
                        <a 
                            href="{{ route('web.positions.edit', $position->id) }}" 
                            class="rounded-lg bg-slate-900 px-3 py-1 text-xs font-semibold text-white hover:bg-slate-800"
                        >
                            Edit Syarat
                        </a>
                        <form 
                            method="POST" 
                            action="{{ route('web.positions.destroy', $position->id) }}" 
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus formasi jabatan {{ $position->title }}?');"
                            class="inline"
                        >
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="rounded-lg p-1 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition" 
                                title="Hapus Jabatan"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
