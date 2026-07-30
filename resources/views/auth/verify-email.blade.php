<x-guest-layout>
    <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Satu Langkah Lagi</p>
    <h1 class="font-bold text-3xl mt-2 tracking-tight" style="font-family: 'Fraunces', serif;">Verifikasi email kamu</h1>
    <p class="mt-3 text-sm text-[#1f4a42] leading-relaxed">Terima kasih sudah mendaftar. Sebelum mulai, klik tautan verifikasi yang sudah kami kirim ke emailmu. Belum menerima emailnya? Kami bisa kirim ulang.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-100 rounded-lg px-3.5 py-2.5">
            Tautan verifikasi baru sudah dikirim ke email yang kamu daftarkan.
        </div>
    @endif

    <div class="mt-8 flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Kirim Ulang Email Verifikasi
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-[#1f4a42] hover:text-[#163832] underline underline-offset-2 focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#163832] rounded">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>