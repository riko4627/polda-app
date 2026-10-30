@extends('layouts.master')

@section('title', 'Manajemen Personel - Biro SDM Polda')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Data Personel Polri</h2>
            <p class="mt-1 text-sm text-slate-500">Kelola direktori data anggota, rekam jejak karir, sertifikasi, dan nilai kinerja.</p>
        </div>
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('web.personnel.create') }}" 
                class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-slate-950 shadow-md shadow-amber-500/20 transition hover:bg-amber-400"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Personel Baru</span>
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

    <!-- Search & Filter Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('web.personnel.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-slate-700">Cari Nama / NRP / Jabatan</label>
                <div class="relative mt-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}" 
                        placeholder="Ketik nama, NRP, atau jabatan..." 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-2 pl-9 text-sm text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                    >
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Rank Filter -->
            <div>
                <label class="block text-xs font-semibold text-slate-700">Pangkat</label>
                <select 
                    name="rank_id" 
                    class="mt-1 w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                >
                    <option value="">Semua Pangkat</option>
                    @foreach ($ranks as $rank)
                        <option value="{{ $rank->id }}" {{ $rankFilter == $rank->id ? 'selected' : '' }}>
                            {{ $rank->code }} - {{ $rank->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Track Filter & Submit -->
            <div class="flex items-end gap-2">
                <div class="w-full">
                    <label class="block text-xs font-semibold text-slate-700">Jalur Fungsi</label>
                    <select 
                        name="function_track" 
                        class="mt-1 w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                    >
                        <option value="">Semua Fungsi</option>
                        @foreach ($functionTracks as $track)
                            <option value="{{ $track }}" {{ $trackFilter == $track ? 'selected' : '' }}>
                                {{ $track }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button 
                    type="submit" 
                    class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Personnel Table Card -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="border-b border-slate-200 bg-slate-50/75 text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-6 py-4">Personel</th>
                        <th class="px-6 py-4">Pangkat</th>
                        <th class="px-6 py-4">Jabatan & Satker</th>
                        <th class="px-6 py-4">Pendidikan</th>
                        <th class="px-6 py-4">Fungsi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($personnelList as $personnel)
                        <tr class="transition hover:bg-slate-50/50">
                            <!-- Name & NRP -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-slate-800 to-slate-900 font-bold text-amber-400">
                                        {{ substr($personnel->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('web.personnel.show', $personnel->id) }}" class="font-bold text-slate-900 hover:text-amber-600">
                                            {{ $personnel->name }}
                                        </a>
                                        <div class="flex items-center gap-2 text-xs text-slate-500">
                                            <span>NRP: {{ $personnel->nrp }}</span>
                                            @if ($personnel->has_sespimmen)
                                                <span class="rounded bg-purple-100 px-1.5 py-0.2 text-[10px] font-semibold text-purple-700">
                                                    Sespimmen
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Rank -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-lg border border-amber-300 bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-800">
                                    {{ $personnel->rank?->code ?? '-' }}
                                </span>
                                <span class="block text-[11px] text-slate-500 mt-0.5">
                                    {{ $personnel->rank?->name ?? '-' }}
                                </span>
                            </td>

                            <!-- Position & Unit -->
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-800">{{ $personnel->current_position ?? 'Belum ada jabatan' }}</span>
                                <span class="block text-xs text-slate-500">{{ $personnel->unit_name ?? '-' }}</span>
                            </td>

                            <!-- Education -->
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-700">{{ $personnel->education_level }}</span>
                                @if ($personnel->education_major)
                                    <span class="block text-xs text-slate-500">{{ $personnel->education_major }}</span>
                                @endif
                            </td>

                            <!-- Track -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">
                                    {{ $personnel->function_track }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a 
                                        href="{{ route('web.personnel.show', $personnel->id) }}" 
                                        class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-amber-600"
                                        title="Lihat Profil & Portofolio"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a 
                                        href="{{ route('web.personnel.edit', $personnel->id) }}" 
                                        class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-blue-600"
                                        title="Edit Data"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                Tidak ada data personel yang cocok dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($personnelList->hasPages())
            <div class="border-t border-slate-200 bg-slate-50/50 px-6 py-3">
                {{ $personnelList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
