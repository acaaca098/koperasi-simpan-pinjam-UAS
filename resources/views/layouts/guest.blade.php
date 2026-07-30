<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Koperasi Sejahtera Bersama') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700,900|inter:400,500,600,700|ibm-plex-mono:500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .ks-stamp { transform: rotate(-9deg); }
            @media (prefers-reduced-motion: reduce) { * { animation: none !important; transition: none !important; } }
        </style>
    </head>
    <body class="bg-[#F7F4EC] font-sans text-[#0d211d] antialiased">

        <div class="min-h-screen grid lg:grid-cols-2">

            {{-- BRAND PANEL --}}
            <div class="hidden lg:flex flex-col justify-between bg-[#163832] text-[#F7F4EC] p-12 relative overflow-hidden">
                <div class="absolute -right-24 -bottom-24 w-96 h-96 rounded-full border border-[#F7F4EC]/10"></div>
                <div class="absolute -right-10 -bottom-40 w-96 h-96 rounded-full border border-[#F7F4EC]/10"></div>

                <a href="{{ route('home') }}" class="flex items-center gap-2.5 relative z-10">
                    <span class="grid place-items-center w-9 h-9 rounded-full bg-[#C99A3D] text-[#163832] font-bold text-sm" style="font-family: 'Fraunces', serif;">KS</span>
                    <span class="font-semibold text-lg" style="font-family: 'Fraunces', serif;">{{ config('app.name', 'Koperasi Sejahtera Bersama') }}</span>
                </a>

                <div class="relative z-10 max-w-sm">
                    <div class="ks-stamp inline-grid place-items-center w-16 h-16 rounded-full border-2 border-[#C99A3D] text-[#C99A3D] text-center leading-none mb-8">
                        <span class="font-bold text-[9px] tracking-wide" style="font-family: 'Fraunces', serif;">TERVERIFIKASI<br>KOPERASI</span>
                    </div>
                    <h2 class="font-semibold text-2xl leading-snug" style="font-family: 'Fraunces', serif;">{{ $quote ?? '"Simpanan anggota tercatat rapi, setiap pinjaman diputuskan bersama."' }}</h2>
                    <p class="mt-4 text-sm text-[#F7F4EC]/70 leading-relaxed">{{ $subquote ?? 'Masuk untuk mengelola simpanan, mengajukan pinjaman, atau memverifikasi pengajuan anggota — sesuai peran kamu di koperasi.' }}</p>
                </div>

                <p class="relative z-10 text-xs text-[#F7F4EC]/50" style="font-family: 'IBM Plex Mono', monospace;">&copy; {{ date('Y') }} {{ config('app.name', 'Koperasi Sejahtera Bersama') }}</p>
            </div>

            {{-- FORM PANEL --}}
            <div class="flex flex-col justify-center px-6 sm:px-16 py-16">
                <div class="w-full max-w-sm mx-auto">
                    <a href="{{ route('home') }}" class="lg:hidden flex items-center gap-2.5 mb-10">
                        <span class="grid place-items-center w-8 h-8 rounded-full bg-[#163832] text-[#F7F4EC] font-bold text-sm" style="font-family: 'Fraunces', serif;">KS</span>
                        <span class="font-semibold text-lg" style="font-family: 'Fraunces', serif;">{{ config('app.name', 'Koperasi Sejahtera Bersama') }}</span>
                    </a>

                    {{ $slot }}
                </div>
            </div>
        </div>

    </body>
</html>