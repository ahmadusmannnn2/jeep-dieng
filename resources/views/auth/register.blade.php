<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar sekarang untuk mulai menyewa Jeep Dieng</p>
    </div>

    {{-- Alert error global --}}
    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
        <div>
            <p class="font-bold text-red-700 text-sm">Pendaftaran gagal! Mohon periksa kembali:</p>
            <ul class="mt-1 space-y-0.5 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li class="text-red-600 text-xs">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        
        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Misal: Budi Santoso"
                class="w-full px-4 py-2.5 rounded-xl border transition focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                {{ $errors->has('name') ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-400' : 'border-gray-200' }}">
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="contoh@gmail.com"
                class="w-full px-4 py-2.5 rounded-xl border transition focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-400' : 'border-gray-200' }}">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="no_hp" class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
            <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" required placeholder="Misal: 08123456789"
                class="w-full px-4 py-2.5 rounded-xl border transition focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                {{ $errors->has('no_hp') ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-400' : 'border-gray-200' }}">
            <x-input-error :messages="$errors->get('no_hp')" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter"
                    class="w-full px-4 py-2.5 rounded-xl border transition focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                    {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-400' : 'border-gray-200' }}">
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Ulangi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 transition focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <x-input-error :messages="$errors->get('password_confirmation')" />
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