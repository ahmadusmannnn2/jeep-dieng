<x-guest-layout>
    <x-auth-session-status class="mb-4 text-center text-sm font-bold text-emerald-600 bg-emerald-50 p-3 rounded-xl" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk ke akun Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="contoh@gmail.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs font-medium" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-emerald-600 hover:text-emerald-500 transition" href="{{ route('password.request') }}">Lupa Password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs font-medium" />
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