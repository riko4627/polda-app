@extends('layouts.master')

@section('title', 'Edit Personel - Biro SDM Polda')

@section('content')
<div class="max-w-4xl space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Perbarui Data Personel</h2>
            <p class="mt-1 text-sm text-slate-500">Edit data profil, kepangkatan, dan penugasan {{ $personnel->name }}.</p>
        </div>
        <a 
            href="{{ route('web.personnel.show', $personnel->id) }}" 
            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
        >
            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Profil</span>
        </a>
    </div>

    <!-- Error Summary -->
    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <p class="font-bold">Terjadi kesalahan pada input data:</p>
            <ul class="mt-1 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <form method="POST" action="{{ route('web.personnel.update', $personnel->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Identitas & Pangkat -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4">1. Identitas Pokok & Kepangkatan</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Nomor Registrasi Pokok (NRP) <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="nrp" 
                            value="{{ old('nrp', $personnel->nrp) }}" 
                            required 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', $personnel->name) }}" 
                            required 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Pangkat Saat Ini <span class="text-rose-500">*</span></label>
                        <select 
                            name="rank_id" 
                            required 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                            @foreach ($ranks as $rank)
                                <option value="{{ $rank->id }}" {{ old('rank_id', $personnel->rank_id) == $rank->id ? 'selected' : '' }}>
                                    {{ $rank->code }} - {{ $rank->name }} ({{ ucfirst($rank->category) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Jalur Fungsi Utama <span class="text-rose-500">*</span></label>
                        <select 
                            name="function_track" 
                            required 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                            @foreach ($functionTracks as $track)
                                <option value="{{ $track }}" {{ old('function_track', $personnel->function_track) == $track ? 'selected' : '' }}>
                                    {{ $track }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Pendidikan & Dikbang -->
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4">2. Pendidikan & Dikbang</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Tingkat Pendidikan Terakhir <span class="text-rose-500">*</span></label>
                        <select 
                            name="education_level" 
                            required 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                            @foreach ($educationLevels as $edu)
                                <option value="{{ $edu }}" {{ old('education_level', $personnel->education_level) == $edu ? 'selected' : '' }}>
                                    {{ $edu }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Jurusan / Program Studi</label>
                        <input 
                            type="text" 
                            name="education_major" 
                            value="{{ old('education_major', $personnel->education_major) }}" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <div class="sm:col-span-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 bg-slate-50 p-3 w-full">
                            <input 
                                type="checkbox" 
                                name="has_sespimmen" 
                                value="1" 
                                {{ old('has_sespimmen', $personnel->has_sespimmen) ? 'checked' : '' }} 
                                class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                            >
                            <div>
                                <span class="text-sm font-semibold text-slate-800">Lulus Sespimmen Polri (Sekolah Staf & Pimpinan Menengah)</span>
                                <span class="block text-xs text-slate-500">Centang jika personel telah menyelesaikan pendidikan Sespimmen.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section 3: Jabatan Saat Ini & Kontak -->
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-amber-600 mb-4">3. Penugasan Saat Ini & Kontak</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Jabatan Sekarang</label>
                        <input 
                            type="text" 
                            name="current_position" 
                            value="{{ old('current_position', $personnel->current_position) }}" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Kesatuan / Satker</label>
                        <input 
                            type="text" 
                            name="unit_name" 
                            value="{{ old('unit_name', $personnel->unit_name) }}" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Nomor Telepon</label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone', $personnel->phone) }}" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Email Kedinasan</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $personnel->email) }}" 
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between border-t border-slate-100 pt-6">
                <!-- Delete Form Trigger -->
                <button 
                    type="button" 
                    onclick="if(confirm('Yakin ingin menghapus personel ini? Semua riwayat dan nilai asesmen akan ikut terhapus.')) document.getElementById('delete-personnel-form').submit();"
                    class="rounded-xl border border-rose-300 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100"
                >
                    Hapus Personel
                </button>

                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('web.personnel.show', $personnel->id) }}" 
                        class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="rounded-xl bg-amber-500 px-6 py-2 text-sm font-semibold text-slate-950 shadow-md shadow-amber-500/20 transition hover:bg-amber-400"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-personnel-form" method="POST" action="{{ route('web.personnel.destroy', $personnel->id) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
