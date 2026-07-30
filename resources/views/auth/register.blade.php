<x-guest-layout>
    <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Daftar</p>
    <h1 class="font-bold text-3xl mt-2 tracking-tight" style="font-family: 'Fraunces', serif;">Buat akun anggota</h1>
    <p class="mt-2 text-sm text-[#1f4a42]">Butuh sebentar — data kamu akan diverifikasi pengurus koperasi.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Sesuai KTP" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button>
            Daftar Sekarang
        </x-primary-button>
    </form>

    <p class="mt-8 text-sm text-[#1f4a42] text-center">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-[#163832] hover:underline">Masuk</a>
    </p>
</x-guest-layout>