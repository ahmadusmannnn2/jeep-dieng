<x-guest-layout>
    {{-- Alert status (misal: setelah reset password) --}}
    <x-auth-session-status class="mb-4 text-center text-sm font-bold text-emerald-600 bg-emerald-50 p-3 rounded-xl" :status="session('status')" />

    {{-- Alert error global (jika ada) --}}
    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
        <div>
            <p class="font-bold text-red-700 text-sm">Login gagal!</p>
            @foreach ($errors->all() as $error)
                <p class="text-red-600 text-xs mt-0.5">{{ $error }}</p>
            @endforeach
        </div>
    </div>
    @endif

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk ke akun Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="contoh@gmail.com"
                class="w-full px-4 py-2.5 rounded-xl border transition focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-400' : 'border-gray-200' }}">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-emerald-600 hover:text-emerald-500 transition" href="{{ route('password.request') }}">Lupa Password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                class="w-full px-4 py-2.5 rounded-xl border transition focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-400' : 'border-gray-200' }}">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="block pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 transition" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full px-8 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-emerald-500 transition shadow-lg hover:shadow-emerald-500/30">Masuk Sekarang</button>
        </div>
        
        <p class="text-center text-sm text-gray-500 mt-4 font-medium">
            Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-500 transition">Daftar Member</a>
        </p>
    </form>
</x-guest-layout>