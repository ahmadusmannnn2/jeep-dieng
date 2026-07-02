@extends('frontend.layouts.app')

@section('title', 'Profil Akun - Jeep Dieng')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Pengaturan Profil</h2>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 space-y-8">
            <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="absolute top-0 right-0 p-8 opacity-5 text-gray-900 transform group-hover:scale-110 transition duration-500">
                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="relative z-10 max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="relative z-10 max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-8">
            <div class="p-6 sm:p-8 bg-white shadow-sm border border-red-50 rounded-3xl relative overflow-hidden group hover:shadow-md hover:border-red-100 transition duration-300">
                <div class="relative z-10 w-full">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection