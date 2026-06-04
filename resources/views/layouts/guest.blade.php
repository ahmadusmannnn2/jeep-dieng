<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Jeep Dieng') }} - Autentikasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50 flex items-center justify-center min-h-screen relative overflow-hidden">
    
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

    <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl shadow-emerald-500/10 border border-gray-100 sm:rounded-3xl relative z-10 mx-4">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
        
        <div class="flex justify-center mb-8">
            <a href="/" class="flex items-center gap-2">
                @if(isset($pengaturan_website) && $pengaturan_website->logo)
                    <img src="{{ asset('storage/' . $pengaturan_website->logo) }}" alt="Logo" class="w-12 h-12 rounded-xl object-contain bg-emerald-500 p-1 shadow-lg shadow-emerald-500/30">
                @else
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                @endif
                <span class="text-3xl font-extrabold text-gray-900 tracking-tight uppercase">{{ $pengaturan_website->nama_website ?? 'JEEP DIENG' }}</span>
            </a>
        </div>
        
        {{ $slot }}
    </div>
</body>
</html>