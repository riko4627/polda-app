@extends('layouts.master')

@section('title', 'Tambah Jabatan & Aturan Syarat Mutlak - Biro SDM Polda')

@section('content')
<div class="max-w-4xl space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Tambah Formasi Jabatan Baru</h2>
            <p class="mt-1 text-sm text-slate-500">Daftarkan formasi jabatan baru beserta konfigurasi syarat mutlak (*Rule-Based Hard Filter*).</p>
        </div>
        <a 
            href="{{ route('web.positions.index') }}" 
            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
        >
            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <p class="font-bold">Periksa input formulir:</p>
            <ul class="mt-1 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <form method="POST" action="{{ route('web.positions.store') }}" class="space-y-6">
            @csrf

            <!-- Section 1: Informasi Jabatan -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4">1. Identitas Jabatan & Kuota</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Kode Unik / Akronim Jabatan <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="code" 
                            value="{{ old('code') }}" 
                            required 
                            placeholder="Contoh: KASAT_LANTAS" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm font-mono uppercase focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                        <span class="text-[11px] text-slate-500">Huruf kapital tanpa spasi (misal: KANIT_INTEL).</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Nama Lengkap Jabatan <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="title" 
                            value="{{ old('title') }}" 
                            required 
                            placeholder="Contoh: Kepala Satuan Lalu Lintas (Kasat Lantas)" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Satuan Kerja / Kesatuan</label>
                        <input 
                            type="text" 
                            name="unit_name" 
                            value="{{ old('unit_name') }}" 
                            placeholder="Contoh: Polres Sleman / Ditlantas Polda" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Kuota Formasi (Kursi Lowongan) <span class="text-rose-500">*</span></label>
                        <input 
                            type="number" 
                            min="1" 
                            name="quota" 
                            value="{{ old('quota', 1) }}" 
                            required 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700">Deskripsi / Uraian Tugas Pokok</label>
                        <textarea 
                            name="description" 
                            rows="2" 
                            placeholder="Penjelasan ringkas fungsi dan tanggung jawab jabatan..." 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Aturan Syarat Mutlak (Rule Builder) -->
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4">2. Aturan Syarat Mutlak (Hard Filter Eliminasi)</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Pangkat Wajib Mutlak -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Pangkat Wajib Mutlak (Exact Match)</label>
                        <select 
                            name="required_rank_id" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                            <option value="">-- Tidak Dipersyaratkan Pangkat Tertentu --</option>
                            @foreach ($ranks as $rank)
                                <option value="{{ $rank->id }}" {{ old('required_rank_id') == $rank->id ? 'selected' : '' }}>
                                    Wajib {{ $rank->code }} - {{ $rank->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-[11px] text-slate-500">Gunakan jika pangkat harus tepat sama (pangkat di atasnya/di bawahnya gugur).</span>
                    </div>

                    <!-- Pangkat Minimal -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Pangkat Minimal (Hierarki &ge;)</label>
                        <select 
                            name="min_rank_id" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                            <option value="">-- Tidak Ada Batas Minimal --</option>
                            @foreach ($ranks as $rank)
                                <option value="{{ $rank->id }}" {{ old('min_rank_id') == $rank->id ? 'selected' : '' }}>
                                    Minimal {{ $rank->code }} (atau jenjang di atasnya)
                                </option>
                            @endforeach
                        </select>
                        <span class="text-[11px] text-slate-500">Gunakan jika jenjang pangkat di atasnya juga diperbolehkan (misal min. Iptu).</span>
                    </div>

                    <!-- Golongan Pangkat -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Golongan Kepangkatan</label>
                        <select 
                            name="required_rank_category" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                            <option value="">-- Bebas Semua Golongan --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ old('required_rank_category') == $cat ? 'selected' : '' }}>
                                    Golongan {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pendidikan Formal Minimal -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Minimal Pendidikan Formal</label>
                        <select 
                            name="min_education_level" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                            <option value="">-- Tidak Dipersyaratkan --</option>
                            @foreach ($educationLevels as $edu)
                                <option value="{{ $edu }}" {{ old('min_education_level') == $edu ? 'selected' : '' }}>
                                    Minimal {{ $edu }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dikbang Sespimmen -->
                    <div class="sm:col-span-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 bg-slate-50 p-3 w-full">
                            <input 
                                type="checkbox" 
                                name="requires_sespimmen" 
                                value="1" 
                                {{ old('requires_sespimmen') ? 'checked' : '' }} 
                                class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                            >
                            <div>
                                <span class="text-sm font-semibold text-slate-800">Wajib Lulus Dikbang Sespimmen Polri</span>
                                <span class="block text-xs text-slate-500">Personel yang belum lulus Sespimmen otomatis didiskualifikasi (TMS).</span>
                            </div>
                        </label>
                    </div>

                    <!-- Sertifikasi Wajib -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Kata Kunci Sertifikasi / Dikjur Wajib</label>
                        <input 
                            type="text" 
                            name="required_certification_keyword" 
                            value="{{ old('required_certification_keyword') }}" 
                            placeholder="Contoh: Penyidik atau Bhabinkamtibmas" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                        <span class="text-[11px] text-slate-500">Sistem akan memeriksa portofolio sertifikat personel dengan kata kunci ini.</span>
                    </div>

                    <!-- Riwayat Jabatan Wajib -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Wajib Pernah Menjabat Sebagai</label>
                        <input 
                            type="text" 
                            name="required_past_position_title" 
                            value="{{ old('required_past_position_title') }}" 
                            placeholder="Contoh: Kapolres atau Kapolsek" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <!-- Jalur Fungsi Wajib -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700">Rekam Jejak Jalur Fungsi Wajib</label>
                        <input 
                            type="text" 
                            name="required_function_track" 
                            value="{{ old('required_function_track') }}" 
                            placeholder="Contoh: Reskrim, Lantas, atau Intelkam" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700">Catatan Aturan</label>
                        <input 
                            type="text" 
                            name="notes" 
                            value="{{ old('notes') }}" 
                            placeholder="Keterangan singkat aturan kelayakan..." 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                <a 
                    href="{{ route('web.positions.index') }}" 
                    class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="rounded-xl bg-amber-500 px-6 py-2 text-sm font-semibold text-slate-950 shadow-md shadow-amber-500/20 transition hover:bg-amber-400"
                >
                    Simpan Formasi & Aturan Mutlak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
