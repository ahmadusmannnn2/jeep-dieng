@extends('frontend.layouts.app')

@section('title', 'Profil Akun - Jeep Dieng')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-8">Pengaturan Profil</h2>

    <div class="space-y-6">
        <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-3xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-3xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white shadow-sm border border-red-50 rounded-3xl">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection