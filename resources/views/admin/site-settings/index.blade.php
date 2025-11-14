@extends('layouts.dashboard')

@section('title', 'Pengaturan Konten Website')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pengaturan Konten Website</h1>
            <p class="text-gray-600 mt-1">Kelola semua konten halaman user dari sini</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Quick Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @php
            $groups = [
                'home' => ['icon' => 'fa-home', 'label' => 'Beranda', 'color' => 'blue', 'description' => 'Konten halaman utama'],
                'profile' => ['icon' => 'fa-building', 'label' => 'Profil', 'color' => 'teal', 'description' => 'Informasi profil sekolah'],
                'contact' => ['icon' => 'fa-address-book', 'label' => 'Kontak', 'color' => 'green', 'description' => 'Alamat, telepon, email, maps'],
                'school_info' => ['icon' => 'fa-info-circle', 'label' => 'Informasi Sekolah', 'color' => 'purple', 'description' => 'Program keahlian, fasilitas, dan prestasi sekolah', 'route' => 'site-settings.group', 'route_params' => ['group' => 'school_info']],
                'agenda' => ['icon' => 'fa-calendar', 'label' => 'Agenda', 'color' => 'yellow', 'description' => 'Jadwal dan agenda kegiatan', 'route' => 'agenda.index'],
                'footer' => ['icon' => 'fa-sitemap', 'label' => 'Footer', 'color' => 'gray', 'description' => 'Bagian footer website'],
            ];
        @endphp

        @foreach($groups as $groupKey => $groupData)
            @php
                $groupSettings = $settings->get($groupKey === 'school_info' ? 'information' : $groupKey, collect());
                $settingsCount = $groupSettings->count();
            @endphp
            <a href="{{ isset($groupData['route']) ? (isset($groupData['route_params']) ? route($groupData['route'], $groupData['route_params']) : route($groupData['route'])) : route('site-settings.group', $groupKey) }}" 
               class="block bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-{{ $groupData['color'] }}-100 rounded-lg flex items-center justify-center">
                            <i class="fas {{ $groupData['icon'] }} text-{{ $groupData['color'] }}-600 text-xl"></i>
                        </div>
                        <span class="bg-{{ $groupData['color'] }}-100 text-{{ $groupData['color'] }}-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            {{ $settingsCount }} item
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $groupData['label'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $groupData['description'] }}</p>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Klik untuk edit</span>
                        <i class="fas fa-arrow-right text-{{ $groupData['color'] }}-600"></i>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection