<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Koperasi Sejahtera Bersama') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700,900|inter:400,500,600,700|ibm-plex-mono:500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .ks-stamp { transform: rotate(-9deg); }
            .ks-tabular { font-variant-numeric: tabular-nums; }
            @media (prefers-reduced-motion: reduce) {
                * { animation: none !important; transition: none !important; }
            }
        </style>
    </head>
    <body class="bg-[#F7F4EC] text-[#0d211d] font-sans antialiased">

        {{-- NAV --}}
        <header class="sticky top-0 z-40 bg-[#F7F4EC]/90 backdrop-blur border-b border-[#d7e4df]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="grid place-items-center w-8 h-8 rounded-full bg-[#163832] text-[#F7F4EC] font-bold text-sm" style="font-family: 'Fraunces', serif;">KS</span>
                    <span class="font-semibold text-lg tracking-tight" style="font-family: 'Fraunces', serif;">{{ config('app.name', 'Koperasi Sejahtera Bersama') }}</span>
                </a>
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-[#1f4a42]">
                    <a href="#keunggulan" class="hover:text-[#163832] transition-colors">Keunggulan</a>
                    <a href="#cara-kerja" class="hover:text-[#163832] transition-colors">Cara Kerja</a>
                    <a href="#untuk-siapa" class="hover:text-[#163832] transition-colors">Untuk Siapa</a>
                    <a href="#kepercayaan" class="hover:text-[#163832] transition-colors">Keamanan</a>
                </nav>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold bg-[#163832] text-[#F7F4EC] px-4 py-2.5 rounded-full hover:bg-[#1f4a42] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C99A3D]">
                            Ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-block text-sm font-medium text-[#1f4a42] hover:text-[#163832] px-3 py-2 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#163832] rounded">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-semibold bg-[#163832] text-[#F7F4EC] px-4 py-2.5 rounded-full hover:bg-[#1f4a42] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C99A3D]">
                                Daftar Jadi Anggota
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </header>

        {{-- HERO --}}
        <section class="max-w-7xl mx-auto px-6 lg:px-8 pt-14 pb-20 lg:pt-20 lg:pb-28 grid lg:grid-cols-2 gap-14 items-center">
            <div>
                <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-[#a97c26] bg-[#fbf3e3] px-3 py-1.5 rounded-full">
                    Simpan Pinjam Digital &middot; Berbasis Gotong Royong
                </span>
                <h1 class="mt-6 font-black text-4xl sm:text-5xl lg:text-[3.4rem] leading-[1.05] tracking-tight" style="font-family: 'Fraunces', serif;">
                    Simpanan tercatat rapi,<br class="hidden sm:block">
                    pinjaman <span class="italic text-[#1f4a42]">tanpa ribet.</span>
                </h1>
                <p class="mt-6 text-lg text-[#1f4a42] max-w-md leading-relaxed">
                    Setor simpanan, ajukan pinjaman, dan pantau angsuran dari satu tempat &mdash; setiap pengajuan diverifikasi berlapis oleh pengurus dan ketua sebelum dana cair.
                </p>
                <div class="mt-9 flex flex-wrap items-center gap-4">
                    @guest
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-[#163832] text-[#F7F4EC] font-semibold px-6 py-3.5 rounded-full hover:bg-[#1f4a42] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C99A3D]">
                                Daftar Jadi Anggota
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                        @endif
                        <a href="{{ route('login') }}" class="font-semibold text-[#1f4a42] px-2 py-3.5 hover:text-[#163832] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#163832] rounded">
                            Sudah anggota? Masuk &rarr;
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-[#163832] text-[#F7F4EC] font-semibold px-6 py-3.5 rounded-full hover:bg-[#1f4a42] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C99A3D]">
                            Ke Dashboard Saya
                        </a>
                    @endguest
                </div>
                <p class="mt-8 text-xs text-[#1f4a42]/70">Diawasi pengurus koperasi &middot; Setiap transaksi tercatat &amp; dapat ditelusuri</p>
            </div>

            {{-- Passbook card --}}
            <div class="relative">
                <div class="absolute -inset-4 bg-[#163832]/5 rounded-[2rem] rotate-2 hidden sm:block"></div>
                <div class="relative bg-white border border-[#d7e4df] rounded-3xl shadow-[0_20px_50px_-15px_rgba(13,33,29,0.25)] overflow-hidden">
                    <div class="bg-[#163832] text-[#F7F4EC] px-7 py-5 flex items-center justify-between">
                        <div>
                            <p class="text-[11px] uppercase tracking-widest text-[#C99A3D] font-semibold">Buku Anggota Digital</p>
                            <p class="text-lg font-semibold mt-0.5" style="font-family: 'Fraunces', serif;">Ringkasan Koperasi</p>
                        </div>
                        <span class="grid place-items-center w-9 h-9 rounded-full border border-[#F7F4EC]/30 text-xs" style="font-family: 'IBM Plex Mono', monospace;">KS</span>
                    </div>
                    <div class="p-7 space-y-5">
                        <div class="flex items-end justify-between border-b border-dashed border-[#d7e4df] pb-4">
                            <div>
                                <p class="text-xs text-[#1f4a42]/70">Anggota Aktif</p>
                                <p class="ks-tabular font-semibold text-3xl mt-1" style="font-family: 'IBM Plex Mono', monospace;">{{ number_format($totalAnggota ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-xs text-[#1f4a42]/60">orang</span>
                        </div>
                        <div class="flex items-end justify-between border-b border-dashed border-[#d7e4df] pb-4">
                            <div>
                                <p class="text-xs text-[#1f4a42]/70">Pinjaman Tersalurkan</p>
                                <p class="ks-tabular font-semibold text-3xl mt-1" style="font-family: 'IBM Plex Mono', monospace;">Rp {{ number_format($totalPinjamanTersalurkan ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-xs text-[#1f4a42]/60">total</span>
                        </div>
                        <div class="flex items-center justify-between pt-1">
                            <p class="text-xs text-[#1f4a42]/70 leading-relaxed max-w-[13rem]">Setiap pengajuan pinjaman diverifikasi pengurus &amp; disetujui ketua sebelum dicairkan.</p>
                            <div class="ks-stamp shrink-0 grid place-items-center w-20 h-20 rounded-full border-[3px] border-[#C99A3D] text-[#C99A3D] text-center leading-tight">
                                <span class="font-bold text-[11px] tracking-wide" style="font-family: 'Fraunces', serif;">TERVERIFIKASI<br>KOPERASI</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- KEUNGGULAN --}}
        <section id="keunggulan" class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-[0.9fr_1.1fr] gap-14 items-start">
                <div>
                    <h2 class="font-bold text-3xl sm:text-4xl tracking-tight" style="font-family: 'Fraunces', serif;">Kenapa lewat koperasi, bukan pinjol?</h2>
                    <p class="mt-4 text-[#1f4a42] leading-relaxed max-w-sm">Koperasi dimiliki bersama oleh anggotanya &mdash; bukan investor luar. Keuntungan diputar kembali untuk kesejahteraan anggota, bukan dibagi ke pihak ketiga.</p>
                </div>
                <div class="grid sm:grid-cols-2 gap-x-8 gap-y-9">
                    <div class="flex gap-4">
                        <span class="shrink-0 grid place-items-center w-10 h-10 rounded-xl bg-[#fbf3e3] text-[#a97c26]">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3v18M5 8h14M5 16h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold" style="font-family: 'Fraunces', serif;">Bunga transparan sejak awal</h3>
                            <p class="text-sm text-[#1f4a42] mt-1.5 leading-relaxed">Simulasi bunga &amp; tenor bisa dilihat sebelum pengajuan dikirim &mdash; tidak ada biaya tersembunyi.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span class="shrink-0 grid place-items-center w-10 h-10 rounded-xl bg-[#fbf3e3] text-[#a97c26]">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4M12 3l8 4v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V7l8-4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold" style="font-family: 'Fraunces', serif;">Berbadan hukum koperasi</h3>
                            <p class="text-sm text-[#1f4a42] mt-1.5 leading-relaxed">Dikelola sesuai AD/ART koperasi dan dipertanggungjawabkan tiap tahun lewat Rapat Anggota Tahunan (RAT).</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span class="shrink-0 grid place-items-center w-10 h-10 rounded-xl bg-[#fbf3e3] text-[#a97c26]">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 19V5a1 1 0 011-1h11l4 4v11a1 1 0 01-1 1H5a1 1 0 01-1-1z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M8 9h6M8 13h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold" style="font-family: 'Fraunces', serif;">Sisa Hasil Usaha (SHU) untuk anggota</h3>
                            <p class="text-sm text-[#1f4a42] mt-1.5 leading-relaxed">Keuntungan koperasi dibagi kembali ke anggota tiap tahun sesuai partisipasi simpanan.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span class="shrink-0 grid place-items-center w-10 h-10 rounded-xl bg-[#fbf3e3] text-[#a97c26]">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.6"/><path d="M12 7.5v5l3 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold" style="font-family: 'Fraunces', serif;">Riwayat transaksi bisa diaudit</h3>
                            <p class="text-sm text-[#1f4a42] mt-1.5 leading-relaxed">Setiap setoran, pinjaman, dan angsuran tercatat dan bisa ditinjau kapan saja oleh anggota.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- UNTUK SIAPA --}}
        <section id="untuk-siapa" class="bg-white border-y border-[#d7e4df]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
                <div class="max-w-xl">
                    <h2 class="font-bold text-3xl sm:text-4xl tracking-tight" style="font-family: 'Fraunces', serif;">Satu platform, tiga peran.</h2>
                    <p class="mt-3 text-[#1f4a42]">Setiap peran punya akses dan tanggung jawabnya masing-masing &mdash; dari pengajuan sampai persetujuan.</p>
                </div>
                <div class="mt-12 grid md:grid-cols-3 gap-6">

                    <div class="rounded-2xl border border-[#d7e4df] p-7 hover:border-[#163832]/30 transition-colors">
                        <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Anggota</p>
                        <h3 class="font-semibold text-xl mt-2" style="font-family: 'Fraunces', serif;">Kelola simpanan &amp; pinjaman sendiri</h3>
                        <ul class="mt-5 space-y-3 text-sm text-[#1f4a42]">
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Setor &amp; tarik simpanan sukarela</li>
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Ajukan pinjaman + unggah jaminan</li>
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Bayar angsuran &amp; unggah bukti transfer</li>
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Lihat riwayat transaksi</li>
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-[#d7e4df] p-7 hover:border-[#163832]/30 transition-colors">
                        <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Pengurus</p>
                        <h3 class="font-semibold text-xl mt-2" style="font-family: 'Fraunces', serif;">Verifikasi &amp; jalankan operasional</h3>
                        <ul class="mt-5 space-y-3 text-sm text-[#1f4a42]">
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Kelola data anggota</li>
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Verifikasi pengajuan pinjaman</li>
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Approve/reject pinjaman nominal kecil</li>
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Kelola jadwal angsuran &amp; laporan</li>
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-[#d7e4df] p-7 hover:border-[#163832]/30 transition-colors">
                        <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Ketua</p>
                        <h3 class="font-semibold text-xl mt-2" style="font-family: 'Fraunces', serif;">Putuskan pinjaman bernilai besar</h3>
                        <ul class="mt-5 space-y-3 text-sm text-[#1f4a42]">
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Approve/reject pinjaman di atas ambang batas</li>
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Lihat laporan keuangan koperasi</li>
                            <li class="flex gap-2.5"><span class="text-[#C99A3D] mt-0.5">&check;</span>Pengawasan akhir sebelum pencairan dana</li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        {{-- CARA KERJA --}}
        <section id="cara-kerja" class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
            <div class="max-w-xl">
                <h2 class="font-bold text-3xl sm:text-4xl tracking-tight" style="font-family: 'Fraunces', serif;">Alur pengajuan pinjaman</h2>
                <p class="mt-3 text-[#1f4a42]">Transparan dari awal sampai dana cair &mdash; anggota mendapat notifikasi otomatis di tiap tahap.</p>
            </div>

            <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">
                <div class="hidden lg:block absolute top-6 left-[12%] right-[12%] h-px bg-[#d7e4df]"></div>

                <div class="relative">
                    <div class="w-12 h-12 rounded-full bg-[#163832] text-[#F7F4EC] font-semibold grid place-items-center relative z-10" style="font-family: 'IBM Plex Mono', monospace;">1</div>
                    <h3 class="font-semibold text-lg mt-5" style="font-family: 'Fraunces', serif;">Ajukan &amp; unggah jaminan</h3>
                    <p class="mt-2 text-sm text-[#1f4a42] leading-relaxed">Anggota mengisi jumlah pinjaman dan tenor, lalu mengunggah dokumen jaminan.</p>
                </div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-full bg-[#163832] text-[#F7F4EC] font-semibold grid place-items-center relative z-10" style="font-family: 'IBM Plex Mono', monospace;">2</div>
                    <h3 class="font-semibold text-lg mt-5" style="font-family: 'Fraunces', serif;">Verifikasi pengurus</h3>
                    <p class="mt-2 text-sm text-[#1f4a42] leading-relaxed">Pengurus memeriksa kelayakan &amp; eligibilitas simpanan anggota.</p>
                </div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-full bg-[#C99A3D] text-[#163832] font-semibold grid place-items-center relative z-10" style="font-family: 'IBM Plex Mono', monospace;">3</div>
                    <h3 class="font-semibold text-lg mt-5" style="font-family: 'Fraunces', serif;">Approval ketua</h3>
                    <p class="mt-2 text-sm text-[#1f4a42] leading-relaxed">Khusus nominal di atas ambang batas, ketua memberi persetujuan akhir.</p>
                </div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-full bg-[#163832] text-[#F7F4EC] font-semibold grid place-items-center relative z-10" style="font-family: 'IBM Plex Mono', monospace;">4</div>
                    <h3 class="font-semibold text-lg mt-5" style="font-family: 'Fraunces', serif;">Dana cair</h3>
                    <p class="mt-2 text-sm text-[#1f4a42] leading-relaxed">Notifikasi otomatis dikirim &amp; jadwal angsuran langsung tersedia.</p>
                </div>
            </div>
        </section>

        {{-- KEPERCAYAAN --}}
        <section id="kepercayaan" class="bg-[#163832] text-[#F7F4EC]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
                <h2 class="font-bold text-3xl sm:text-4xl tracking-tight max-w-lg" style="font-family: 'Fraunces', serif;">Dibangun di atas kepercayaan anggota.</h2>
                <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-10">
                    <div>
                        <p class="text-[#C99A3D] text-sm" style="font-family: 'IBM Plex Mono', monospace;">01</p>
                        <h3 class="font-semibold text-lg mt-2" style="font-family: 'Fraunces', serif;">Akses sesuai peran</h3>
                        <p class="text-sm text-[#F7F4EC]/70 mt-2 leading-relaxed">Anggota, pengurus, dan ketua hanya melihat &amp; mengelola apa yang menjadi haknya.</p>
                    </div>
                    <div>
                        <p class="text-[#C99A3D] text-sm" style="font-family: 'IBM Plex Mono', monospace;">02</p>
                        <h3 class="font-semibold text-lg mt-2" style="font-family: 'Fraunces', serif;">Persetujuan berlapis</h3>
                        <p class="text-sm text-[#F7F4EC]/70 mt-2 leading-relaxed">Pinjaman besar tidak bisa cair tanpa persetujuan ketua.</p>
                    </div>
                    <div>
                        <p class="text-[#C99A3D] text-sm" style="font-family: 'IBM Plex Mono', monospace;">03</p>
                        <h3 class="font-semibold text-lg mt-2" style="font-family: 'Fraunces', serif;">Jejak dokumen tersimpan</h3>
                        <p class="text-sm text-[#F7F4EC]/70 mt-2 leading-relaxed">Jaminan &amp; bukti transfer tersimpan dan bisa diverifikasi kapan saja.</p>
                    </div>
                    <div>
                        <p class="text-[#C99A3D] text-sm" style="font-family: 'IBM Plex Mono', monospace;">04</p>
                        <h3 class="font-semibold text-lg mt-2" style="font-family: 'Fraunces', serif;">Notifikasi real-time</h3>
                        <p class="text-sm text-[#F7F4EC]/70 mt-2 leading-relaxed">Setiap perubahan status pengajuan langsung diberitahukan ke anggota.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="max-w-7xl mx-auto px-6 lg:px-8 py-20 text-center">
            <h2 class="font-bold text-3xl sm:text-4xl tracking-tight" style="font-family: 'Fraunces', serif;">Sudah jadi anggota koperasi?</h2>
            <p class="mt-3 text-[#1f4a42]">Ajukan pinjaman atau setor simpanan kapan saja, langsung dari akun kamu.</p>
            @guest
                <a href="{{ route('login') }}" class="mt-8 inline-flex items-center gap-2 bg-[#163832] text-[#F7F4EC] font-semibold px-7 py-3.5 rounded-full hover:bg-[#1f4a42] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C99A3D]">
                    Masuk ke Akun Saya
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="mt-8 inline-flex items-center gap-2 bg-[#163832] text-[#F7F4EC] font-semibold px-7 py-3.5 rounded-full hover:bg-[#1f4a42] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C99A3D]">
                    Ke Dashboard Saya
                </a>
            @endguest
        </section>

        {{-- FOOTER --}}
        <footer class="border-t border-[#d7e4df]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <span class="grid place-items-center w-7 h-7 rounded-full bg-[#163832] text-[#F7F4EC] font-bold text-xs" style="font-family: 'Fraunces', serif;">KS</span>
                    <span class="font-semibold" style="font-family: 'Fraunces', serif;">{{ config('app.name', 'Koperasi Sejahtera Bersama') }}</span>
                </div>
                <p class="text-xs text-[#1f4a42]/70">&copy; {{ date('Y') }} {{ config('app.name', 'Koperasi Sejahtera Bersama') }}. Simpan pinjam berbasis gotong royong.</p>
            </div>
        </footer>

    </body>
</html>