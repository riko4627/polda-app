<header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-800 bg-slate-900/95 px-4 backdrop-blur sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Toggle -->
        <button 
            type="button" 
            onclick="toggleSidebar()" 
            class="inline-flex items-center justify-center rounded-lg p-2 text-slate-400 hover:bg-slate-800 hover:text-white focus:outline-none lg:hidden"
            aria-label="Toggle Sidebar"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Context Title & Breadcrumb -->
        <div class="hidden sm:block">
            <div class="flex items-center gap-2 text-xs font-medium text-amber-400">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                <span>BIRO SDM POLDA &bull; BAGBINKAR</span>
            </div>
            <h1 class="text-sm font-semibold tracking-wide text-white sm:text-base">
                {{ $title ?? 'Sistem Pendukung Keputusan Penempatan Personel' }}
            </h1>
        </div>
    </div>

    <!-- Right Header Utilities -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Badge Periode Seleksi Wanjak -->
        <div class="hidden md:flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-xs font-medium text-amber-300">
            <svg class="h-3.5 w-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Sidang Wanjak 2026</span>
        </div>

        <!-- Mode Indicator / Status Engine -->
        <div class="flex items-center gap-2 rounded-lg border border-slate-800 bg-slate-800/60 px-3 py-1.5 text-xs text-slate-300">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            <span class="font-medium hidden sm:inline">Engine:</span>
            <span class="font-semibold text-emerald-400">TOPSIS Ready</span>
        </div>

        <!-- User Profile Card -->
        <div class="flex items-center gap-3 border-l border-slate-800 pl-3 sm:pl-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-tr from-amber-600 to-amber-400 font-bold text-slate-950 shadow-md">
                P
            </div>
            <div class="hidden text-left sm:block">
                <p class="text-xs font-semibold text-white">Administrator SDM</p>
                <p class="text-[11px] text-slate-400">Bagbinkar Biro SDM</p>
            </div>
        </div>
    </div>
</header>
