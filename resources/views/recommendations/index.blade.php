@extends('layouts.master')

@section('title', 'Seleksi Rekomendasi SPK-TOPSIS - Biro SDM Polda')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Simulasi Rekomendasi Sidang Wanjak</h2>
            <p class="mt-1 text-sm text-slate-500">Pilih jabatan target dan jalankan kalkulasi eliminasi syarat mutlak & pemeringkatan TOPSIS.</p>
        </div>
        <a 
            href="{{ route('web.recommendations.history') }}" 
            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
        >
            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Lihat Riwayat Sidang</span>
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('error'))
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Form Launch Card (2 Kolom) -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-4 mb-6">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500 text-slate-950 font-black text-sm">
                    ⚡
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Form Parameter Eksekusi Rekomendasi</h3>
                    <p class="text-xs text-slate-500">Sistem akan menyaring seluruh personel aktif dan merangking yang memenuhi syarat.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('web.recommendations.run') }}" class="space-y-6">
                @csrf

                <!-- Pilih Jabatan Target -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Pilih Jabatan Target Formasi <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="position_id" 
                        required 
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                    >
                        <option value="">-- Pilih Salah Satu Jabatan --</option>
                        @foreach ($positions as $pos)
                            <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>
                                {{ $pos->title }} (Kuota: {{ $pos->quota }} Kursi) &bull; [{{ $pos->code }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Periode Tahun & Catatan -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Tahun Periode Penilaian <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="period_year" 
                            required 
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-2.5 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                            @for ($y = 2026; $y >= 2023; $y--)
                                <option value="{{ $y }}" {{ old('period_year', 2026) == $y ? 'selected' : '' }}>
                                    Tahun Anggaran {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Keterangan / Catatan Wanjak
                        </label>
                        <input 
                            type="text" 
                            name="notes" 
                            value="{{ old('notes', 'Sidang Dewan Pertimbangan Karir (Wanjak) Periode Berjalan') }}" 
                            placeholder="Contoh: Sidang Wanjak Semester 1 2026" 
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>
                </div>

                <!-- Action Button -->
                <div class="border-t border-slate-100 pt-6">
                    <button 
                        type="submit" 
                        class="flex w-full items-center justify-center gap-3 rounded-xl bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 py-3.5 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/25 transition hover:from-amber-400 hover:to-amber-600 cursor-pointer"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Jalankan Analisis Rekomendasi SPK-TOPSIS</span>
                    </button>
                    <p class="mt-2 text-center text-xs text-slate-400">
                        Proses membutuhkan &lt; 1 detik untuk menyaring dan mengkalkulasi seluruh kandidat secara presisi.
                    </p>
                </div>
            </form>
        </div>

        <!-- Sidebar Info: Ringkasan 7 Profil Jabatan -->
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 mb-3">7 Profil Jabatan Target</h3>
                <div class="space-y-2.5 text-xs">
                    @foreach ($positions as $pos)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/70 p-2.5">
                            <div>
                                <p class="font-bold text-slate-900">{{ $pos->title }}</p>
                                <span class="text-[11px] text-slate-500">Kuota: {{ $pos->quota }} &bull; {{ $pos->unit_name ?? '-' }}</span>
                            </div>
                            <span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800">
                                {{ $pos->code }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Box Edukasi Arsitektur -->
            <div class="rounded-2xl border border-slate-800 bg-slate-950 p-5 text-white text-xs">
                <p class="font-bold uppercase tracking-wider text-amber-400 mb-2">Transparansi Penilaian (Explainable):</p>
                <p class="text-slate-300 leading-relaxed">
                    Setelah kalkulasi selesai, Anda dapat mengakses <strong>3 Tab Penjelasan Lengkap</strong>: Peringkat Lolos Sesuai Kuota, Personel TMS beserta alasan diskualifikasi, serta matriks matematis ilmiah ($X, R, Y, D^+, D^-$).
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
