<x-app-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Anggota</p>
        <h2 class="font-bold text-2xl mt-1 tracking-tight" style="font-family: 'Fraunces', serif;">Ajukan Pinjaman</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 text-sm text-[#a64b33] bg-red-50 border border-red-100 rounded-lg px-4 py-3 space-y-1">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-[#d7e4df] p-6 sm:p-8">
                <form method="POST" action="{{ route('pinjaman.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="jumlah" value="Jumlah Pengajuan (Rp)" />
                        <x-text-input id="jumlah" type="number" name="jumlah" :value="old('jumlah')" min="100000" required placeholder="mis. 5000000" />
                        <p class="text-xs text-[#1f4a42]/60 mt-1.5">Minimal Rp 100.000. Nominal di atas ambang batas butuh persetujuan ketua.</p>
                    </div>

                    <div>
                        <x-input-label for="tenor_bulan" value="Tenor (bulan)" />
                        <x-text-input id="tenor_bulan" type="number" name="tenor_bulan" :value="old('tenor_bulan')" min="1" max="36" required placeholder="mis. 12" />
                    </div>

                   <div x-data="{ fileName: '' }">
    <x-input-label for="jaminan" value="Unggah Jaminan" />
    <label for="jaminan"
           class="mt-1.5 flex flex-col items-center justify-center gap-2 border-2 border-dashed rounded-xl px-4 py-8 text-center cursor-pointer transition-colors"
           :class="fileName ? 'border-[#163832]/40 bg-[#F7F4EC]' : 'border-[#d7e4df] hover:border-[#163832]/40 hover:bg-[#F7F4EC]'">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" class="text-[#1f4a42]/50"><path d="M12 16V4M12 4l-4 4M12 4l4 4M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span class="text-sm font-medium text-[#163832]" x-text="fileName ? 'File terpilih: ' + fileName : 'Klik untuk unggah dokumen jaminan'"></span>
        <span class="text-xs text-[#1f4a42]/60" x-show="!fileName">PDF, JPG, atau PNG &middot; maks 5MB</span>
        <input id="jaminan" type="file" name="jaminan" accept=".pdf,.jpg,.jpeg,.png" required class="sr-only"
               @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''">
    </label>
    <x-input-error :messages="$errors->get('jaminan')" />
</div>

                    <x-primary-button class="w-fit px-7">
                        Kirim Pengajuan
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>