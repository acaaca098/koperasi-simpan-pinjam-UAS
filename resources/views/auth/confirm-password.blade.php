<x-guest-layout>
    <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Keamanan</p>
    <h1 class="font-bold text-3xl mt-2 tracking-tight" style="font-family: 'Fraunces', serif;">Konfirmasi kata sandi</h1>
    <p class="mt-2 text-sm text-[#1f4a42] leading-relaxed">Ini area sensitif — masukkan kembali kata sandi kamu sebelum melanjutkan.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button>
            Konfirmasi
        </x-primary-button>
    </form>
</x-guest-layout>