@extends('layouts.master')

@section('title', 'Riwayat Sidang Wanjak - Biro SDM Polda')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Riwayat Keputusan Sidang Wanjak</h2>
            <p class="mt-1 text-sm text-slate-500">Arsip seluruh proses kalkulasi dan rekomendasi penempatan jabatan yang pernah dieksekusi.</p>
        </div>
        <a 
            href="{{ route('web.recommendations.index') }}" 
            class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow-md shadow-amber-500/20 transition hover:bg-amber-400"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>Mulai Simulasi Baru</span>
        </a>
    </div>

    <!-- Table Card -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-6 py-4">Waktu Eksekusi</th>
                        <th class="px-6 py-4">Jabatan Target</th>
                        <th class="px-6 py-4">Tahun Periode</th>
                        <th class="px-6 py-4">Kandidat Dinilai</th>
                        <th class="px-6 py-4">Status Hasil</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($runs as $run)
                        <tr class="transition hover:bg-slate-50/50">
                            <!-- Date & Time -->
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900">{{ $run->run_date->format('d M Y') }}</span>
                                <span class="block text-xs text-slate-500">{{ $run->run_date->format('H:i') }} WIB</span>
                            </td>

                            <!-- Position -->
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900">{{ $run->position?->title }}</span>
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <span class="font-mono text-amber-700 font-semibold">{{ $run->position?->code }}</span>
                                    <span>&bull;</span>
                                    <span>Kuota: {{ $run->position?->quota }} Kursi</span>
                                </div>
                            </td>

                            <!-- Period -->
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                T.A. {{ $run->period_year }}
                            </td>

                            <!-- Candidate Breakdown -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700">
                                        {{ $run->eligible_candidates }} Lolos (MS)
                                    </span>
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700">
                                        {{ $run->total_candidates - $run->eligible_candidates }} TMS
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    {{ ucfirst($run->status) }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 text-center">
                                <a 
                                    href="{{ route('web.recommendations.show', $run->id) }}" 
                                    class="inline-flex items-center gap-1 rounded-xl bg-slate-900 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-slate-800"
                                >
                                    <span>Buka 3 Tab Hasil</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                Belum ada riwayat pelaksanaan rekomendasi Wanjak.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($runs->hasPages())
            <div class="border-t border-slate-200 bg-slate-50/50 px-6 py-3">
                {{ $runs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
