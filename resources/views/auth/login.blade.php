<x-guest-layout>
    <p class="text-xs font-semibold uppercase tracking-widest text-[#a97c26]">Masuk</p>
    <h1 class="font-bold text-3xl mt-2 tracking-tight" style="font-family: 'Fraunces', serif;">Selamat datang kembali</h1>
    <p class="mt-2 text-sm text-[#1f4a42]">Masuk dengan akun anggota, pengurus, atau ketua koperasi.</p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="flex items-center justify-between text-sm">
            <label for="remember_me" class="inline-flex items-center gap-2 text-[#1f4a42]">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-[#d7e4df] text-[#163832] focus:ring-[#163832]">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a class="text-[#1f4a42] hover:text-[#163832] underline underline-offset-2" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <x-primary-button>
            Masuk
        </x-primary-button>
    </form>

    @if (Route::has('register'))
        <p class="mt-8 text-sm text-[#1f4a42] text-center">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-[#163832] hover:underline">Daftar jadi anggota</a>
        </p>
    @endif
</x-guest-layout>