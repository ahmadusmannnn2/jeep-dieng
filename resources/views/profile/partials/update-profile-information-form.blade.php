<section x-data="{ photoName: null, photoPreview: null }">
    <header>
        <h2 class="text-xl font-extrabold text-gray-900">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi profil akun dan alamat email Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Foto Profil -->
        <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-2xl border border-gray-100">
            <!-- Current Profile Photo -->
            <div class="relative shrink-0" x-show="!photoPreview">
                @if($user->foto_profil)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($user->foto_profil) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-white">
                @else
                    <div class="w-24 h-24 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-4xl shadow-md border-4 border-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <!-- New Profile Photo Preview -->
            <div class="relative shrink-0" x-show="photoPreview" style="display: none;">
                <span class="block w-24 h-24 rounded-full bg-cover bg-no-repeat bg-center shadow-md border-4 border-emerald-400"
                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <div class="flex-1">
                <label for="foto_profil" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition ease-in-out cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pilih Foto Baru
                    <input type="file" id="foto_profil" name="foto_profil" class="hidden" 
                           x-ref="photo"
                           x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                           " />
                </label>
                <p class="mt-2 text-xs text-gray-500 font-medium">Format: JPG, PNG, WEBP. Maksimal 2MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('foto_profil')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 transition shadow-sm" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 transition shadow-sm" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Alamat email Anda belum diverifikasi.

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-600">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white font-extrabold rounded-xl hover:bg-emerald-600 transition shadow-md shadow-emerald-500/30">Simpan Perubahan</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full"
                >Berhasil Disimpan!</p>
            @endif
        </div>
    </form>
</section>
