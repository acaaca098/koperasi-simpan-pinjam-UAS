<x-app-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Anggota</p>
        <h2 class="font-bold text-2xl mt-1 tracking-tight" style="font-family: 'Fraunces', serif;">Pinjaman Saya</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 text-sm text-green-700 bg-green-50 border border-green-100 rounded-lg px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 text-sm text-[#a64b33] bg-red-50 border border-red-100 rounded-lg px-4 py-3 space-y-1">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-[#1f4a42]/70">Riwayat pengajuan pinjaman dan jadwal angsuran kamu.</p>
                <a href="{{ route('pinjaman.create') }}"
                   class="shrink-0 px-4 py-2 rounded-lg bg-[#163832] text-[#F7F4EC] text-xs font-semibold hover:bg-[#1f4a42] transition-colors">
                    + Ajukan Pinjaman
                </a>
            </div>

            @forelse ($pinjaman as $p)
                @php
                    $statusColor = match($p->status) {
                        'DIAJUKAN' => 'bg-gray-100 text-gray-700',
                        'DIVERIFIKASI' => 'bg-blue-50 text-blue-700',
                        'DISETUJUI' => 'bg-amber-50 text-amber-700',
                        'DICAIRKAN' => 'bg-emerald-50 text-emerald-700',
                        'LUNAS' => 'bg-emerald-50 text-emerald-700',
                        'DITOLAK' => 'bg-red-50 text-red-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <div class="bg-white rounded-2xl border border-[#d7e4df] p-6 mb-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-lg font-semibold" style="font-family: 'IBM Plex Mono', monospace;">
                                Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-[#1f4a42]/60 mt-0.5">
                                Tenor {{ $p->tenor_bulan }} bulan &middot; diajukan {{ $p->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusColor }}">
                            {{ $p->status }}
                        </span>
                    </div>

                    @if (in_array($p->status, ['DICAIRKAN', 'LUNAS']) && $p->angsuran->isNotEmpty())
                        <div class="mt-5 border-t border-[#eef3f1] pt-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26] mb-3">Jadwal Angsuran</p>
                            <div class="space-y-2">
                                @foreach ($p->angsuran as $a)
                                    <div class="flex items-center justify-between gap-3 text-sm py-2 border-b border-[#f3f6f5] last:border-0">
                                        <div>
                                            <span class="font-medium">Angsuran ke-{{ $a->angsuran_ke }}</span>
                                            <span class="text-[#1f4a42]/60"> &middot; jatuh tempo {{ $a->jatuh_tempo->format('d M Y') }}</span>
                                            @if ($a->denda > 0)
                                                <span class="text-[#a64b33]"> &middot; denda Rp {{ number_format($a->denda, 0, ',', '.') }}</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-3 shrink-0">
                                            <span style="font-family: 'IBM Plex Mono', monospace;">
                                                Rp {{ number_format($a->jumlah_tagihan, 0, ',', '.') }}
                                            </span>

                                            @if (in_array($a->status, ['BELUM_BAYAR', 'TELAT']))
                                                <form method="POST" action="{{ route('angsuran.bayar', $a) }}" enctype="multipart/form-data" class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="file" name="bukti_transfer" accept=".pdf,.jpg,.jpeg,.png" required
                                                           class="text-xs file:mr-2 file:py-1.5 file:px-2 file:rounded-md file:border-0 file:bg-[#F7F4EC] file:text-xs w-40">
                                                    <button type="submit"
                                                        class="px-3 py-1.5 rounded-lg {{ $a->status === 'TELAT' ? 'bg-[#a64b33]' : 'bg-[#163832]' }} text-white text-xs font-semibold hover:opacity-90 transition-opacity">
                                                        {{ $a->status === 'TELAT' ? 'Bayar (Telat)' : 'Bayar' }}
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                                    {{ $a->status === 'LUNAS' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                                    {{ $a->status === 'LUNAS' ? 'Lunas' : 'Menunggu Verifikasi' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-[#d7e4df] p-12 text-center">
                    <p class="text-[#1f4a42] font-medium">Belum ada pengajuan pinjaman.</p>
                    <p class="text-sm text-[#1f4a42]/60 mt-1">Klik "Ajukan Pinjaman" untuk mulai mengajukan.</p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $pinjaman->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
