<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar sekarang untuk mulai menyewa Jeep Dieng</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        
        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Misal: Budi Santoso" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-xs font-medium" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="contoh@gmail.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs font-medium" />
        </div>

        <div>
            <label for="no_hp" class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
            <input id="no_hp" type="number" name="no_hp" value="{{ old('no_hp') }}" required placeholder="Misal: 08123456789" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            <x-input-error :messages="$errors->get('no_hp')" class="mt-1 text-red-500 text-xs font-medium" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs font-medium" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Ulangi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs font-medium" />
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full px-8 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Daftar Sekarang</button>
        </div>
        
        <p class="text-center text-sm text-gray-500 mt-4 font-medium">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-500 transition">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>