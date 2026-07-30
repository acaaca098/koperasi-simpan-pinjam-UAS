<x-guest-layout>
    <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Reset Kata Sandi</p>
    <h1 class="font-bold text-3xl mt-2 tracking-tight" style="font-family: 'Fraunces', serif;">Lupa kata sandi?</h1>
    <p class="mt-2 text-sm text-[#1f4a42] leading-relaxed">Tidak masalah. Masukkan email kamu, kami akan kirimkan tautan untuk membuat kata sandi baru.</p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button>
            Kirim Tautan Reset
        </x-primary-button>
    </form>
</x-guest-layout>