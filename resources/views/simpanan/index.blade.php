<x-app-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Anggota</p>
        <h2 class="font-bold text-2xl mt-1 tracking-tight" style="font-family: 'Fraunces', serif;">Simpanan Saya</h2>
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

            @if ($simpanan->isEmpty())
                <div class="bg-white rounded-2xl border border-[#d7e4df] p-12 text-center">
                    <p class="text-[#1f4a42] font-medium">Belum ada rekening simpanan.</p>
                    <p class="text-sm text-[#1f4a42]/60 mt-1">Hubungi pengurus koperasi untuk membuka simpanan pokok kamu.</p>
                </div>
            @else
                <div class="grid md:grid-cols-3 gap-5 mb-10">
                    @foreach ($simpanan as $s)
                        @php
                            $jenisLabel = match($s->jenis) {
                                'pokok' => 'Simpanan Pokok',
                                'wajib' => 'Simpanan Wajib',
                                'sukarela' => 'Simpanan Sukarela',
                                default => ucfirst($s->jenis),
                            };
                        @endphp
                        <div class="bg-white rounded-2xl border border-[#d7e4df] p-6">
                            <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">{{ $jenisLabel }}</p>
                            <p class="mt-3 text-2xl font-semibold" style="font-family: 'IBM Plex Mono', monospace;">Rp {{ number_format($s->saldo, 0, ',', '.') }}</p>

                            <div class="mt-5 space-y-2">
                                <form method="POST" action="{{ route('simpanan.setor', $s) }}" class="flex gap-2">
                                    @csrf
                                    <input type="number" name="jumlah" min="10000" required placeholder="Nominal setor"
                                           class="min-w-0 flex-1 rounded-lg border-[#d7e4df] text-sm py-2 px-3 focus:border-[#163832] focus:ring-[#163832]">
                                    <button type="submit" class="shrink-0 px-3.5 py-2 rounded-lg bg-[#163832] text-[#F7F4EC] text-xs font-semibold hover:bg-[#1f4a42] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C99A3D]">
                                        Setor
                                    </button>
                                </form>

                                @if ($s->jenis === 'sukarela')
                                    <form method="POST" action="{{ route('simpanan.tarik', $s) }}" class="flex gap-2">
                                        @csrf
                                        <input type="number" name="jumlah" min="10000" required placeholder="Nominal tarik"
                                               class="min-w-0 flex-1 rounded-lg border-[#d7e4df] text-sm py-2 px-3 focus:border-[#163832] focus:ring-[#163832]">
                                        <button type="submit" class="shrink-0 px-3.5 py-2 rounded-lg bg-white border border-[#d7e4df] text-[#1f4a42] text-xs font-semibold hover:bg-[#F7F4EC] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#163832]">
                                        Tarik
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <h3 class="font-semibold text-lg mb-4" style="font-family: 'Fraunces', serif;">Riwayat Transaksi Terbaru</h3>
                <div class="bg-white rounded-2xl border border-[#d7e4df] divide-y divide-[#eef3f1] overflow-hidden">
                    @php $riwayat = $simpanan->flatMap->transaksi->sortByDesc('tanggal')->take(10); @endphp
                    @forelse ($riwayat as $t)
                        <div class="p-4 sm:p-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium">{{ $t->jenis === 'setor' ? 'Setor Simpanan' : 'Tarik Simpanan' }}</p>
                                <p class="text-xs text-[#1f4a42]/60 mt-0.5">{{ $t->tanggal->format('d M Y') }}</p>
                            </div>
                            <span class="font-semibold text-sm {{ $t->jenis === 'setor' ? 'text-[#163832]' : 'text-[#a64b33]' }}" style="font-family: 'IBM Plex Mono', monospace;">
                                {{ $t->jenis === 'setor' ? '+' : '-' }}Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-sm text-[#1f4a42]/60">Belum ada transaksi.</div>
                    @endforelse
                </div>
            @endif

        </div>
    </div>
</x-app-layout>