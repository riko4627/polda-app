@extends('layouts.master')

@section('title', 'Konfigurasi Bobot TOPSIS - ' . $position->title)

@section('content')
<div class="max-w-4xl space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Pengaturan Bobot Kriteria TOPSIS</h2>
            <p class="mt-1 text-sm text-slate-500">Jabatan: <strong class="text-slate-800">{{ $position->title }}</strong> &bull; Total bobot wajib berjumlah <strong>100% (1.0)</strong>.</p>
        </div>
        <a 
            href="{{ route('web.positions.show', $position->id) }}" 
            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
        >
            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Alert Flash -->
    @if (session('error'))
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Live Total Validator Bar (Sticky) -->
    <div class="sticky top-20 z-20 rounded-2xl border border-slate-800 bg-slate-900 p-4 text-white shadow-lg backdrop-blur">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Total Bobot Terhitung:</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span id="totalPercentDisplay" class="text-2xl font-black text-amber-400">0%</span>
                    <span id="totalDecimalDisplay" class="text-xs font-mono text-slate-400">(w = 0.0000)</span>
                </div>
            </div>

            <div id="validationStatusBadge" class="flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold">
                <span id="statusDot" class="h-2.5 w-2.5 rounded-full"></span>
                <span id="statusText">Memeriksa Bobot...</span>
            </div>
        </div>

        <!-- Visual Progress Bar -->
        <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-slate-800">
            <div id="progressBar" class="h-full transition-all duration-300 w-0 bg-amber-500"></div>
        </div>
    </div>

    <!-- Form Weights Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <form method="POST" action="{{ route('web.positions.weights.update', $position->id) }}" id="weightsForm" class="space-y-4">
            @csrf

            <div class="divide-y divide-slate-100">
                @foreach ($allCriteria as $criterion)
                    @php
                        $weightValue = old("weights.{$criterion->id}", $currentWeightsMap[$criterion->id] ?? $criterion->default_weight);
                    @endphp
                    <div class="flex flex-col gap-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="max-w-md">
                            <div class="flex items-center gap-2">
                                <span class="rounded bg-slate-900 px-2 py-0.5 font-mono text-xs font-bold text-amber-400">
                                    {{ $criterion->code }}
                                </span>
                                <h4 class="font-bold text-slate-900">{{ $criterion->name }}</h4>
                                <span class="rounded px-2 py-0.5 text-[10px] font-bold uppercase {{ $criterion->type === 'cost' ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $criterion->type }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">{{ $criterion->description }}</p>
                        </div>

                        <!-- Input Weight & Quick Buttons -->
                        <div class="flex items-center gap-3">
                            <div class="relative w-32">
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    max="1" 
                                    name="weights[{{ $criterion->id }}]" 
                                    id="weight_{{ $criterion->id }}" 
                                    value="{{ $weightValue }}" 
                                    class="weight-input w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-2 text-right text-sm font-bold text-slate-900 focus:border-amber-500 focus:bg-white focus:outline-none"
                                >
                            </div>
                            <span class="w-12 text-xs font-semibold text-slate-500" id="percent_label_{{ $criterion->id }}">
                                {{ round($weightValue * 100) }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between border-t border-slate-100 pt-6">
                <button 
                    type="button" 
                    onclick="resetToDefault()" 
                    class="rounded-xl border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50"
                >
                    Reset ke Bobot Default
                </button>

                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('web.positions.show', $position->id) }}" 
                        class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        id="saveWeightsButton" 
                        class="rounded-xl bg-amber-500 px-6 py-2 text-sm font-semibold text-slate-950 shadow-md shadow-amber-500/20 transition hover:bg-amber-400 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Simpan Bobot Kriteria
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const defaultWeights = {
        @foreach ($allCriteria as $criterion)
            "{{ $criterion->id }}": {{ $criterion->default_weight }},
        @endforeach
    };

    function recalculateTotal() {
        const inputs = document.querySelectorAll('.weight-input');
        let total = 0.0;

        inputs.forEach(input => {
            const val = parseFloat(input.value) || 0;
            total += val;

            const criterionId = input.name.match(/weights\[(.*?)\]/)[1];
            const percentLabel = document.getElementById('percent_label_' + criterionId);
            if (percentLabel) {
                percentLabel.textContent = Math.round(val * 100) + '%';
            }
        });

        const percent = Math.round(total * 1000) / 10;
        document.getElementById('totalPercentDisplay').textContent = percent + '%';
        document.getElementById('totalDecimalDisplay').textContent = '(w = ' + total.toFixed(4) + ')';

        const progressBar = document.getElementById('progressBar');
        const badge = document.getElementById('validationStatusBadge');
        const dot = document.getElementById('statusDot');
        const text = document.getElementById('statusText');
        const saveBtn = document.getElementById('saveWeightsButton');

        progressBar.style.width = Math.min(percent, 100) + '%';

        // Check if sum equals 1.0 (allow slight precision tolerance)
        if (Math.abs(total - 1.0) < 0.001) {
            badge.className = 'flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold bg-emerald-500/20 border border-emerald-500/40 text-emerald-300';
            dot.className = 'h-2.5 w-2.5 rounded-full bg-emerald-400';
            text.textContent = 'Bobot Valid (Pas 100%)';
            progressBar.className = 'h-full transition-all duration-300 bg-emerald-500';
            saveBtn.disabled = false;
        } else {
            badge.className = 'flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300';
            dot.className = 'h-2.5 w-2.5 rounded-full bg-rose-400';
            const diff = Math.round((1.0 - total) * 1000) / 10;
            text.textContent = (diff > 0) ? `Kurang ${diff}%` : `Lebih ${Math.abs(diff)}%`;
            progressBar.className = 'h-full transition-all duration-300 bg-rose-500';
            saveBtn.disabled = true;
        }
    }

    function resetToDefault() {
        for (const [id, weight] of Object.entries(defaultWeights)) {
            const input = document.getElementById('weight_' + id);
            if (input) {
                input.value = weight;
            }
        }
        recalculateTotal();
    }

    document.querySelectorAll('.weight-input').forEach(input => {
        input.addEventListener('input', recalculateTotal);
    });

    document.addEventListener('DOMContentLoaded', recalculateTotal);
</script>
@endpush
