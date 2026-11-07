@extends('layouts.master')

@section('title', 'Edit Akun: ' . $user->name)

@section('content')
<div class="max-w-2xl space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Edit Akun User</h2>
            <p class="mt-1 text-sm text-slate-500">Perbarui data akun <span class="font-semibold text-slate-700">{{ $user->name }}</span>.</p>
        </div>
        <a 
            href="{{ route('web.users.index') }}" 
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
        <form method="POST" action="{{ route('web.users.update', $user->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Role Info (read-only) -->
            <div class="flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3.5">
                <svg class="h-5 w-5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <div>
                    <p class="text-xs font-bold text-amber-800">Role: Super Admin</p>
                    <p class="text-[11px] text-amber-700">Role tidak dapat diubah. Akun ini memiliki akses penuh ke seluruh sistem.</p>
                </div>
            </div>

            <!-- Nama -->
            <div>
                <label class="block text-xs font-semibold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name', $user->name) }}" 
                    required 
                    class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                >
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-semibold text-slate-700">Email <span class="text-rose-500">*</span></label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}" 
                    required 
                    class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                >
            </div>

            <!-- No. HP -->
            <div>
                <label class="block text-xs font-semibold text-slate-700">No. HP / Telepon</label>
                <input 
                    type="text" 
                    name="phone" 
                    value="{{ old('phone', $user->phone) }}" 
                    placeholder="Contoh: 08123456789"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                >
            </div>

            <!-- Password Baru (opsional) -->
            <div class="border-t border-slate-100 pt-5">
                <p class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-400">Ganti Password (opsional)</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Password Baru</label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Kosongkan jika tidak ingin mengganti password"
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700">Konfirmasi Password Baru</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            placeholder="Ulangi password baru"
                            class="mt-1 w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                    </div>
                </div>
            </div>

            <!-- Status Aktif -->
            <div>
                <label class="inline-flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 bg-slate-50 p-3 w-full">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1" 
                        {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                    >
                    <div>
                        <span class="text-sm font-semibold text-slate-800">Akun Aktif</span>
                        <span class="block text-xs text-slate-500">Nonaktifkan untuk menangguhkan akses login sementara.</span>
                    </div>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                <a 
                    href="{{ route('web.users.index') }}" 
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
        </form>
    </div>
</div>
@endsection
