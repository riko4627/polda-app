<!DOCTYPE html>
<html lang="id" class="min-h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Penempatan Personel Polri</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 font-sans antialiased flex items-center justify-center p-4">

    <main class="w-full max-w-sm">

        {{-- Header --}}
        <div class="mb-6 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500 shadow-lg shadow-amber-500/30">
                <svg class="h-8 w-8 text-slate-900" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2l2.4 6.6 7 .6-5.3 4.6 1.6 6.9-5.7-3.5-5.7 3.5 1.6-6.9L2.6 9.2l7-.6L12 2z" />
                </svg>
            </div>
            <h1 class="mt-4 text-xl font-extrabold tracking-tight text-white sm:text-2xl">Sistem Penempatan Personel</h1>
            <p class="mt-1 text-sm text-slate-400">Biro SDM &bull; Polda SULTENG</p>
        </div>

        {{-- Kartu --}}
        <div class="rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
            <h2 class="text-base font-bold text-slate-900">Masuk sebagai Super Admin</h2>

            @if (session('success'))
                <div role="status" class="mt-4 rounded-lg bg-emerald-50 px-3 py-2.5 text-xs text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div role="alert" class="mt-4 rounded-lg bg-rose-50 px-3 py-2.5 text-xs text-rose-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700">Email</label>
                    <input
                        id="email" type="email" name="email" value="{{ old('email') }}"
                        required autofocus autocomplete="username" inputmode="email" autocapitalize="none"
                        placeholder="nama@polda.polri.go.id"
                        class="mt-1.5 w-full rounded-lg border bg-slate-50 px-3.5 py-2.5 text-base text-slate-900 placeholder-slate-400 transition focus:bg-white focus:outline-none focus:ring-2 sm:text-sm
                            {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-200 focus:border-amber-500 focus:ring-amber-200' }}"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700">Kata Sandi</label>
                    <div class="relative mt-1.5">
                        <input
                            id="password" type="password" name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2.5 pl-3.5 pr-11 text-base text-slate-900 placeholder-slate-400 transition focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-200 sm:text-sm"
                        >
                        <button
                            type="button" id="togglePassword"
                            aria-label="Tampilkan kata sandi"
                            class="absolute inset-y-0 right-0 px-3.5 text-xs font-semibold text-slate-400 hover:text-slate-700 focus:outline-none focus-visible:text-amber-600"
                        >Lihat</button>
                    </div>
                </div>

                <label for="remember" class="flex cursor-pointer items-center gap-2 text-xs text-slate-600">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-300">
                    Ingat saya di perangkat ini
                </label>

                <button
                    type="submit" id="submitBtn"
                    class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 active:scale-[0.99] disabled:opacity-70"
                >
                    Masuk
                </button>
            </form>

            {{-- Hanya tampil di environment local --}}
            @if (app()->environment('local'))
                <button type="button" id="fillDemo" class="mt-4 w-full text-center text-[11px] text-slate-400 hover:text-amber-600">
                    Mode dev: isi kredensial otomatis
                </button>
            @endif
        </div>

        <p class="mt-6 text-center text-[11px] text-slate-500">
            &copy; {{ date('Y') }} POLDA SULTENG &bull; Biro SDM
        </p>
    </main>

    <script>
        const pw = document.getElementById('password');
        const toggle = document.getElementById('togglePassword');
        toggle.addEventListener('click', () => {
            const show = pw.type === 'password';
            pw.type = show ? 'text' : 'password';
            toggle.textContent = show ? 'Sembunyi' : 'Lihat';
            toggle.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });

        const form = document.getElementById('loginForm');
        const btn = document.getElementById('submitBtn');
        form.addEventListener('submit', () => {
            if (form.checkValidity()) { btn.disabled = true; btn.textContent = 'Memverifikasi...'; }
        });
        window.addEventListener('pageshow', e => {
            if (e.persisted) { btn.disabled = false; btn.textContent = 'Masuk'; }
        });

        const fill = document.getElementById('fillDemo');
        if (fill) fill.addEventListener('click', () => {
            document.getElementById('email').value = 'superadmin@polda.polri.go.id';
            pw.value = 'password';
        });
    </script>
</body>
</html>
