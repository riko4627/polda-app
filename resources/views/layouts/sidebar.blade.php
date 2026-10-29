<!-- Backdrop for Mobile -->
<div 
    id="sidebarBackdrop" 
    onclick="toggleSidebar()" 
    class="fixed inset-0 z-40 bg-slate-950/80 backdrop-blur-sm transition-opacity duration-300 lg:hidden hidden"
></div>

<!-- Sidebar Component -->
<aside 
    id="mainSidebar" 
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-800 bg-slate-950 text-slate-300 transition-transform duration-300 ease-in-out -translate-x-full lg:static lg:translate-x-0"
>
    <!-- Brand Header -->
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-800 px-6">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 shadow-lg shadow-amber-500/20">
                <!-- Lambang Bintang Tribrata Presisi -->
                <svg class="h-6 w-6 text-slate-950" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l2.4 6.6 7 .6-5.3 4.6 1.6 6.9-5.7-3.5-5.7 3.5 1.6-6.9L2.6 9.2l7-.6L12 2z" />
                </svg>
            </div>
            <div>
                <span class="block text-sm font-black tracking-wider text-white">POLRI PRESISI</span>
                <span class="block text-[10px] font-semibold tracking-wider text-amber-400">SPK-TOPSIS SDM</span>
            </div>
        </a>

        <!-- Close button on Mobile -->
        <button 
            type="button" 
            onclick="toggleSidebar()" 
            class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-900 hover:text-white lg:hidden"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Menu Items -->
    <div class="flex flex-1 flex-col overflow-y-auto px-4 py-5 scrollbar-thin scrollbar-thumb-slate-800">
        <!-- Section: Menu Utama -->
        <div class="mb-6">
            <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">Menu Utama</p>
            <div class="mt-2 space-y-1">
                <!-- Dashboard -->
                <a 
                    href="{{ url('/') }}" 
                    class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->is('/') ? 'bg-amber-500 text-slate-950 font-semibold shadow-md shadow-amber-500/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 {{ request()->is('/') ? 'text-slate-950' : 'text-amber-400 group-hover:text-amber-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Rekomendasi SPK -->
                <a 
                    href="{{ url('/recommendations') }}" 
                    class="group flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->is('recommendations*') ? 'bg-amber-500 text-slate-950 font-semibold shadow-md shadow-amber-500/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                >
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 {{ request()->is('recommendations*') ? 'text-slate-950' : 'text-amber-400 group-hover:text-amber-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Seleksi SPK-TOPSIS</span>
                    </div>
                    <span class="rounded bg-amber-400/20 px-1.5 py-0.5 text-[10px] font-bold text-amber-300 group-hover:bg-amber-400/30">
                        Hybrid
                    </span>
                </a>
            </div>
        </div>

        <!-- Section: Master Data SDM -->
        <div class="mb-6">
            <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">Master Data SDM</p>
            <div class="mt-2 space-y-1">
                <!-- Personel -->
                <a 
                    href="{{ url('/personnel') }}" 
                    class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->is('personnel*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 {{ request()->is('personnel*') ? 'text-slate-950' : 'text-slate-400 group-hover:text-amber-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Data Personel</span>
                </a>

                <!-- Jabatan & Syarat Mutlak -->
                <a 
                    href="{{ url('/positions') }}" 
                    class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->is('positions*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 {{ request()->is('positions*') ? 'text-slate-950' : 'text-slate-400 group-hover:text-amber-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Jabatan & Syarat</span>
                </a>

                <!-- Kriteria Penilaian -->
                <a 
                    href="{{ url('/criteria') }}" 
                    class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->is('criteria*') ? 'bg-amber-500 text-slate-950 font-semibold' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 {{ request()->is('criteria*') ? 'text-slate-950' : 'text-slate-400 group-hover:text-amber-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Kriteria & Bobot</span>
                </a>
            </div>
        </div>

        <!-- Section: Laporan & Sidang Wanjak -->
        <div>
            <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">Sidang Wanjak</p>
            <div class="mt-2 space-y-1">
                <a 
                    href="{{ url('/recommendations/history') }}" 
                    class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-900 hover:text-white"
                >
                    <svg class="h-5 w-5 text-slate-400 group-hover:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Riwayat Keputusan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Sidebar Footer Info -->
    <div class="shrink-0 border-t border-slate-800 p-4">
        <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-3">
            <div class="flex items-center gap-2 text-xs font-semibold text-white">
                <span class="inline-block h-2 w-2 rounded-full bg-amber-400"></span>
                <span>Arsitektur Hybrid</span>
            </div>
            <p class="mt-1 text-[11px] leading-relaxed text-slate-400">
                Penyaringan syarat mutlak + Perangkingan TOPSIS dinamis per jabatan.
            </p>
        </div>
    </div>
</aside>
