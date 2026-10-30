<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SPK Penempatan Personel Kepolisian (SPK-TOPSIS)') - POLRI PRESISI</title>

    <!-- Google Fonts / Instrument Sans -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="flex min-h-screen bg-slate-100 font-sans text-slate-800">
    <!-- Sidebar Partials -->
    @include('layouts.sidebar')

    <!-- Main Content Area -->
    <div class="flex flex-1 flex-col overflow-hidden">
        <!-- Navbar Partials -->
        @include('layouts.navbar')

        <!-- Page Main Slot -->
        <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
            @yield('content')
        </main>

        <!-- Footer Partials -->
        @include('layouts.footer')
    </div>

    <!-- Toggle Script for Mobile Responsive Sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
