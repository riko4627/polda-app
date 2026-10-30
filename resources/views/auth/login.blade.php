<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Super Admin - SPK Penempatan Personel Polri</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-100 bg-slate-950 selection:bg-amber-500 selection:text-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-x-hidden">

    <!-- Background Decorative Presisi Elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:24px_24px] opacity-20"></div>
    </div>

    <div class="relative sm:mx-auto sm:w-full sm:max-w-md px-4">
        <!-- Logo & Header Branding -->
        <div class="text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 shadow-xl shadow-amber-500/20 ring-1 ring-amber-400/40">
                <!-- Lambang Bintang Tribrata Presisi -->
                <svg class="h-9 w-9 text-slate-950" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l2.4 6.6 7 .6-5.3 4.6 1.6 6.9-5.7-3.5-5.7 3.5 1.6-6.9L2.6 9.2l7-.6L12 2z" />
                </svg>
            </div>

            <div class="mt-4 flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-widest text-amber-400">
                <span class="inline-block h-2 w-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>POLRI PRESISI &bull; BIRO SDM</span>
            </div>

            <h1 class="mt-2 text-xl font-black tracking-tight text-white sm:text-2xl">
                SPK PENEMPATAN PERSONEL
            </h1>
            <p class="mt-1 text-xs text-slate-400">
                Sistem Pendukung Keputusan Sidang Wanjak (Metode TOPSIS Hybrid)
            </p>

            <!-- Role Badge -->
            <div class="mt-3 inline-flex items-center gap-1.5 rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-300">
                <svg class="h-3.5 w-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Akses Khusus: Super Administrator</span>
            </div>
        </div>

        <!-- Card Container -->
        <div class="mt-6">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/90 p-6 sm:p-8 shadow-2xl backdrop-blur-xl">

                <!-- Flash Alert Success -->
                @if (session('success'))
                    <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3.5 text-xs text-emerald-300">
                        <svg class="h-5 w-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Flash Alert Error -->
                @if ($errors->any())
                    <div class="mb-5 flex items-start gap-3 rounded-xl border border-rose-500/30 bg-rose-500/10 p-3.5 text-xs text-rose-300">
                        <svg class="h-5 w-5 shrink-0 text-rose-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Form Login -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    <!-- Field: Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300">
                            Alamat Email Super Admin
                        </label>
                        <div class="relative mt-1.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email', 'superadmin@polda.polri.go.id') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder="superadmin@polda.polri.go.id" 
                                class="w-full rounded-xl border border-slate-700 bg-slate-950/70 py-2.5 pl-10 pr-4 text-sm text-white placeholder-slate-500 shadow-inner focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                        </div>
                    </div>

                    <!-- Field: Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-300">
                            Kata Sandi Kedinasan
                        </label>
                        <div class="relative mt-1.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                value="password"
                                required 
                                autocomplete="current-password"
                                placeholder="••••••••" 
                                class="w-full rounded-xl border border-slate-700 bg-slate-950/70 py-2.5 pl-10 pr-10 text-sm text-white placeholder-slate-500 shadow-inner focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                            <!-- Show/Hide Password Toggle -->
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-300 focus:outline-none"
                                title="Lihat/Sembunyikan Kata Sandi"
                            >
                                <svg id="eyeIcon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between text-xs">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-slate-300">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                id="remember" 
                                checked
                                class="h-4 w-4 rounded border-slate-700 bg-slate-950 text-amber-500 focus:ring-amber-500/30"
                            >
                            <span>Ingat sesi di perangkat ini</span>
                        </label>
                        <span class="text-slate-500">Biro SDM Polda</span>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="group relative flex w-full justify-center rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 py-2.5 px-4 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/25 transition duration-200 hover:from-amber-400 hover:to-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-950 active:scale-[0.99]"
                        >
                            <span class="flex items-center gap-2">
                                <span>Masuk ke Dashboard Presisi</span>
                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Quick Credential Helper Box -->
                <div class="mt-6 rounded-xl border border-slate-800 bg-slate-950/60 p-3.5 text-xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 font-bold text-amber-400">
                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                            <span>Kredensial Super Admin Bawaan:</span>
                        </div>
                        <button 
                            type="button" 
                            onclick="fillCredentials()" 
                            class="rounded bg-amber-500/10 px-2 py-0.5 text-[11px] font-semibold text-amber-300 hover:bg-amber-500/20 transition"
                        >
                            Klik untuk Isi Otomatis
                        </button>
                    </div>
                    <div class="mt-2 space-y-1 font-mono text-[11px] text-slate-300">
                        <p>Email: <span class="text-white font-bold">superadmin@polda.polri.go.id</span></p>
                        <p>Sandi: <span class="text-white font-bold">password</span></p>
                    </div>
                </div>

            </div>

            <!-- Footer Security Note -->
            <p class="mt-6 text-center text-[11px] leading-relaxed text-slate-500">
                &copy; {{ date('Y') }} Kepolisian Daerah &bull; Biro Sumber Daya Manusia (SDM).<br>
                Sistem Terbatas untuk Dewan Pertimbangan Karir (Wanjak) & Bagbinkar.
            </p>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />`;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        }

        function fillCredentials() {
            document.getElementById('email').value = 'superadmin@polda.polri.go.id';
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
