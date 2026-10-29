@extends('layouts.master')

@section('title', 'Master Kriteria Penilaian - Biro SDM Polda')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Master Kriteria Penilaian (TOPSIS)</h2>
            <p class="mt-1 text-sm text-slate-500">Kelola parameter evaluasi kinerja, tipe kriteria (*Benefit* / *Cost*), skala nilai, dan bobot bawaan.</p>
        </div>
        <button 
            type="button" 
            onclick="openModal('createCriterionModal')" 
            class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow-md shadow-amber-500/20 transition hover:bg-amber-400"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Tambah Kriteria Baru</span>
        </button>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <p class="font-bold">Gagal memproses data kriteria:</p>
            <ul class="mt-1 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Information Box: Benefit vs Cost -->
    <div class="rounded-2xl border border-slate-800 bg-slate-950 p-6 text-white shadow-md">
        <h3 class="text-sm font-bold uppercase tracking-wider text-amber-400">Prinsip Matematis Tipe Kriteria TOPSIS:</h3>
        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 text-xs leading-relaxed text-slate-300">
            <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-3.5">
                <span class="inline-block rounded bg-emerald-500/20 px-2 py-0.5 font-bold text-emerald-400 uppercase mb-1">
                    Tipe Benefit (Keuntungan)
                </span>
                <p>Semakin tinggi skor nilai yang diperoleh personel, semakin diprioritaskan oleh sistem ($A^+ = \max(y_{ij})$). Contoh: Nilai SMK, Psikologi, Jasmani TKJ, MDDP, dan Piagam Prestasi.</p>
            </div>
            <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-3.5">
                <span class="inline-block rounded bg-rose-500/20 px-2 py-0.5 font-bold text-rose-400 uppercase mb-1">
                    Tipe Cost (Beban / Biaya)
                </span>
                <p>Semakin rendah skor nilai yang diperoleh personel, semakin baik peringkatnya ($A^+ = \min(y_{ij})$). Contoh: Catatan Pelanggaran Disiplin / KEPP (0 = Bersih/Terbaik).</p>
            </div>
        </div>
    </div>

    <!-- Criteria Table Card -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Nama Kriteria</th>
                        <th class="px-6 py-4">Tipe Kriteria</th>
                        <th class="px-6 py-4">Skala Nilai</th>
                        <th class="px-6 py-4">Bobot Bawaan</th>
                        <th class="px-6 py-4">Deskripsi / Penjelasan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($criteria as $criterion)
                        <tr class="transition hover:bg-slate-50/50">
                            <!-- Code -->
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">
                                <span class="rounded bg-slate-900 px-2.5 py-1 text-xs text-amber-400">
                                    {{ $criterion->code }}
                                </span>
                            </td>

                            <!-- Name -->
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $criterion->name }}
                            </td>

                            <!-- Type -->
                            <td class="px-6 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold uppercase {{ $criterion->type === 'cost' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $criterion->type }}
                                </span>
                            </td>

                            <!-- Scale -->
                            <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                                {{ (int) $criterion->scale_min }} &ndash; {{ (int) $criterion->scale_max }}
                            </td>

                            <!-- Default Weight -->
                            <td class="px-6 py-4 font-semibold text-slate-900">
                                {{ round($criterion->default_weight * 100, 1) }}%
                                <span class="block text-[11px] text-slate-400 font-normal">w = {{ $criterion->default_weight }}</span>
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4 text-xs text-slate-600 max-w-sm leading-relaxed">
                                {{ $criterion->description ?? '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <button 
                                        type="button" 
                                        onclick="openModal('editCriterionModal_{{ $criterion->id }}')" 
                                        class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition"
                                    >
                                        Edit
                                    </button>
                                    <form 
                                        method="POST" 
                                        action="{{ route('web.criteria.destroy', $criterion->id) }}" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria {{ $criterion->name }} ({{ $criterion->code }})?');"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="rounded-lg p-1 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition" 
                                            title="Hapus Kriteria"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Kriteria -->
                        <div id="editCriterionModal_{{ $criterion->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 hidden">
                            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl text-left">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900">Edit Kriteria: {{ $criterion->code }}</h3>
                                        <p class="text-xs text-slate-500">Perbarui parameter penilaian kriteria TOPSIS</p>
                                    </div>
                                    <button type="button" onclick="closeModal('editCriterionModal_{{ $criterion->id }}')" class="text-slate-400 hover:text-slate-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <form method="POST" action="{{ route('web.criteria.update', $criterion->id) }}" class="mt-4 space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700">Nama Kriteria <span class="text-rose-500">*</span></label>
                                        <input 
                                            type="text" 
                                            name="name" 
                                            value="{{ old('name', $criterion->name) }}" 
                                            required 
                                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                                        >
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700">Tipe Kriteria <span class="text-rose-500">*</span></label>
                                            <select name="type" required class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none">
                                                <option value="benefit" {{ old('type', $criterion->type) === 'benefit' ? 'selected' : '' }}>Benefit (Keuntungan)</option>
                                                <option value="cost" {{ old('type', $criterion->type) === 'cost' ? 'selected' : '' }}>Cost (Beban/Biaya)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700">Bobot Bawaan (0.00 - 1.00) <span class="text-rose-500">*</span></label>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                max="1" 
                                                name="default_weight" 
                                                value="{{ old('default_weight', $criterion->default_weight) }}" 
                                                required 
                                                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                                            >
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700">Skala Minimal <span class="text-rose-500">*</span></label>
                                            <input 
                                                type="number" 
                                                step="1" 
                                                name="scale_min" 
                                                value="{{ old('scale_min', (int)$criterion->scale_min) }}" 
                                                required 
                                                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700">Skala Maksimal <span class="text-rose-500">*</span></label>
                                            <input 
                                                type="number" 
                                                step="1" 
                                                name="scale_max" 
                                                value="{{ old('scale_max', (int)$criterion->scale_max) }}" 
                                                required 
                                                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                                            >
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700">Deskripsi / Uraian</label>
                                        <textarea 
                                            name="description" 
                                            rows="2" 
                                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                                        >{{ old('description', $criterion->description) }}</textarea>
                                    </div>

                                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                                        <button 
                                            type="button" 
                                            onclick="closeModal('editCriterionModal_{{ $criterion->id }}')" 
                                            class="rounded-xl border border-slate-300 px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                        >
                                            Batal
                                        </button>
                                        <button 
                                            type="submit" 
                                            class="rounded-xl bg-amber-500 px-4 py-1.5 text-xs font-bold text-slate-950 hover:bg-amber-400"
                                        >
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                Belum ada kriteria penilaian yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kriteria Baru -->
<div id="createCriterionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 hidden">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl text-left">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">Tambah Parameter Kriteria Baru</h3>
                <p class="text-xs text-slate-500">Kriteria baru untuk penilaian TOPSIS personel</p>
            </div>
            <button type="button" onclick="closeModal('createCriterionModal')" class="text-slate-400 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('web.criteria.store') }}" class="mt-4 space-y-4">
            @csrf

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700">Kode <span class="text-rose-500">*</span></label>
                    <input 
                        type="text" 
                        name="code" 
                        value="{{ old('code') }}" 
                        placeholder="C7" 
                        required 
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm font-mono uppercase focus:border-amber-500 focus:outline-none"
                    >
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-700">Nama Kriteria <span class="text-rose-500">*</span></label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        placeholder="Contoh: Nilai Uji Menembak" 
                        required 
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                    >
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700">Tipe Kriteria <span class="text-rose-500">*</span></label>
                    <select name="type" required class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none">
                        <option value="benefit" {{ old('type') === 'cost' ? '' : 'selected' }}>Benefit (Keuntungan)</option>
                        <option value="cost" {{ old('type') === 'cost' ? 'selected' : '' }}>Cost (Beban/Biaya)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700">Bobot Bawaan (w) <span class="text-rose-500">*</span></label>
                    <input 
                        type="number" 
                        step="0.01" 
                        min="0" 
                        max="1" 
                        name="default_weight" 
                        value="{{ old('default_weight', '0.10') }}" 
                        required 
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                    >
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700">Skala Minimal <span class="text-rose-500">*</span></label>
                    <input 
                        type="number" 
                        step="1" 
                        name="scale_min" 
                        value="{{ old('scale_min', 0) }}" 
                        required 
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700">Skala Maksimal <span class="text-rose-500">*</span></label>
                    <input 
                        type="number" 
                        step="1" 
                        name="scale_max" 
                        value="{{ old('scale_max', 100) }}" 
                        required 
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700">Deskripsi / Uraian</label>
                <textarea 
                    name="description" 
                    rows="2" 
                    placeholder="Penjelasan kriteria penilaian ini..." 
                    class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none"
                >{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button 
                    type="button" 
                    onclick="closeModal('createCriterionModal')" 
                    class="rounded-xl border border-slate-300 px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="rounded-xl bg-amber-500 px-4 py-1.5 text-xs font-bold text-slate-950 hover:bg-amber-400"
                >
                    Simpan Kriteria Baru
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Tutup modal jika klik di luar area modal
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('backdrop-blur-sm')) {
            e.target.classList.add('hidden');
        }
    });
</script>
@endsection
