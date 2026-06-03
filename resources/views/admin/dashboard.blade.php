@extends('admin.layouts.app')

@section('title', 'Dashboard Admin - Jeep Dieng')
@section('header_title', 'Overview Dashboard')
@section('header_subtitle', $namaKomunitas)

@section('content')
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl p-8 text-white shadow-lg shadow-emerald-500/20">
        <h3 class="text-3xl font-bold mb-2">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h3>
        <p class="text-emerald-100 max-w-xl">Pantau pesanan Jeep dan atur ketersediaan supir dengan mudah. Semoga harimu menyenangkan dan penyewaan hari ini ramai!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Pesanan</p>
                <h4 class="text-3xl font-bold text-gray-900">{{ $totalPesanan }}</h4>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition">
            <div class="w-16 h-16 rounded-2xl bg-gray-50 text-gray-700 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Armada Jeep</p>
                <h4 class="text-3xl font-bold text-gray-900">{{ $totalJeep }}</h4>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition">
            <div class="w-16 h-16 rounded-2xl bg-gray-50 text-gray-700 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Data Supir</p>
                <h4 class="text-3xl font-bold text-gray-900">{{ $totalSupir }}</h4>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition">
            <div class="w-16 h-16 rounded-2xl bg-gray-50 text-gray-700 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Customer</p>
                <h4 class="text-3xl font-bold text-gray-900">{{ $totalCustomer }}</h4>
            </div>
        </div>
    </div>
@endsection