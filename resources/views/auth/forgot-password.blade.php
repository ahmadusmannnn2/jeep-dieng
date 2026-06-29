<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password?</h2>
        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center text-sm font-bold text-emerald-600 bg-emerald-50 p-3 rounded-xl" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="contoh@gmail.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs font-medium" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full px-8 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
                Kirim Link Reset Password
            </button>
        </div>

        <p class="text-center text-sm text-gray-500 mt-4 font-medium">
            Kembali ke <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-500 transition">Halaman Login</a>
        </p>
    </form>
</x-guest-layout>