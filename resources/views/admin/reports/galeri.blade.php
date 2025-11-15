@extends('layouts.dashboard')

@section('title', 'Laporan Sistem - Admin')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Statistik Sistem</h1>
            <p class="text-gray-600 mt-1">Ringkasan statistik galeri, agenda, user, informasi, serta aktivitas like/dislike. Data dapat diexport ke PDF.</p>
        </div>
        <a href="{{ route('galeri.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Filter Laporan</h2>
        <form method="GET" action="{{ route('galeri.report') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="kategori_id" id="kategori_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $kategoriId == $category->id ? 'selected' : '' }}>
                            {{ $category->judul }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('galeri.report') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Galeri Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Galeri</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_galeries'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-images text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Foto Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Foto</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_photos'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-image text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Agenda Card -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Agenda</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_agendas'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Rata-rata Foto/Galeri Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Rata-rata Foto/Galeri</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['avg_photos_per_gallery'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- System Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total User -->
        <div class="bg-gradient-to-br from-sky-500 to-sky-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total User Terdaftar</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_users'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Informasi -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Informasi</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_informasi'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Like -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Like pada Foto</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_likes'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-thumbs-up text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Dislike -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Dislike pada Foto</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_dislikes'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-thumbs-down text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Galeri Aktif Card -->
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Galeri Aktif</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_aktif_galleries'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Galeri Nonaktif Card -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Galeri Nonaktif</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_nonaktif_galleries'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Agenda Aktif Card -->
        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Agenda Aktif</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_aktif_agendas'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Agenda Nonaktif Card -->
        <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Agenda Nonaktif</p>
                    <p class="text-3xl font-bold mt-2">{{ $statistics['total_nonaktif_agendas'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-calendar-times text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    @if(!empty($statistics['categories_count']))
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Statistik per Kategori</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Galeri</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($statistics['categories_count'] as $categoryName => $count)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $categoryName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2" style="max-width: 200px;">
                                    @php
                                        $percentage = $statistics['total_galeries'] > 0 ? ($count / $statistics['total_galeries']) * 100 : 0;
                                    @endphp
                                    <div class="bg-teal-600 h-2 rounded-full progress-bar" data-percentage="{{ $percentage }}"></div>
                                </div>
                                <span>{{ number_format($percentage, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Export Button -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Export Laporan</h2>
                <p class="text-gray-600 mt-1">Download laporan dalam format PDF dengan filter yang aktif</p>
            </div>
            <form method="GET" action="{{ route('galeri.report.pdf') }}">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <input type="hidden" name="kategori_id" value="{{ $kategoriId }}">
                <input type="hidden" name="status" value="{{ $status }}">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>Download PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Gallery List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-800">Detail Galeri</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Foto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($galeries as $index => $galeri)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $galeri->post->judul ?? 'Tanpa Judul' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $galeri->post->kategori->judul ?? 'Tanpa Kategori' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $galeri->fotos->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($galeri->status === 'aktif')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $galeri->post->created_at ? $galeri->post->created_at->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada data galeri</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update progress bars with actual percentages
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(function(bar) {
        const percentage = bar.getAttribute('data-percentage');
        bar.style.width = percentage + '%';
    });
});
</script>
@endsection