<x-guest-layout>
    <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Reset Kata Sandi</p>
    <h1 class="font-bold text-3xl mt-2 tracking-tight" style="font-family: 'Fraunces', serif;">Buat kata sandi baru</h1>
    <p class="mt-2 text-sm text-[#1f4a42]">Pastikan kata sandi barumu mudah diingat tapi sulit ditebak.</p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi Baru" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button>
            Simpan Kata Sandi Baru
        </x-primary-button>
    </form>
</x-guest-layout>