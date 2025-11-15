<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Galeri Edu</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        .content-transition {
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar-scrollable {
            /* Area menu yang bisa discroll di dalam sidebar */
            scrollbar-width: thin;
            scrollbar-color: #3b82f6 #1e40af;
        }
        .sidebar-scrollable::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scrollable::-webkit-scrollbar-track {
            background: #1e40af;
        }
        .sidebar-scrollable::-webkit-scrollbar-thumb {
            background-color: #3b82f6;
            border-radius: 3px;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div x-data="{ sidebarOpen: true, mobileSidebarOpen: false }" class="min-h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white sidebar-transition flex flex-col h-screen"
             :class="{ '-translate-x-full': !sidebarOpen && !mobileSidebarOpen, 'translate-x-0': sidebarOpen || mobileSidebarOpen }">
            
            <!-- Logo -->
            <div class="flex items-center justify-between p-6 border-b border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                    </div>
                    <h1 class="text-xl font-bold">Galeri Edu</h1>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-white hover:text-blue-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Navigation Menu (scrollable area) -->
            <div class="mt-6 px-4 flex-1 overflow-y-auto sidebar-scrollable">
                <div class="space-y-2">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>Beranda</span>
                    </a>

                    <a href="{{ route('kategori.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('kategori.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-folder w-5"></i>
                        <span>Kategori</span>
                    </a>

                    <a href="{{ route('petugas.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('petugas.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Petugas</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-user-graduate w-5"></i>
                        <span>User</span>
                    </a>

                    <a href="{{ route('galeri.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('galeri.index') || request()->routeIs('galeri.create') || request()->routeIs('galeri.edit') || request()->routeIs('galeri.show') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-images w-5"></i>
                        <span>Galeri</span>
                    </a>

                    <a href="{{ route('galeri.report') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('galeri.report') || request()->routeIs('galeri.report.pdf') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Laporan Statistik</span>
                    </a>

                    <a href="{{ route('galeri.like-logs') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('galeri.like-logs') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-list-ul w-5"></i>
                        <span>Riwayat Like/Dislike</span>
                    </a>

                    <a href="{{ route('admin.informasi-items.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.informasi-items.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-bullhorn w-5"></i>
                        <span>Informasi Terbaru</span>
                    </a>

                    <a href="{{ route('site-settings.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('site-settings.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-cog w-5"></i>
                        <span>Pengaturan Situs</span>
                    </a>

                    <a href="{{ route('admin.profile') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.profile') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-user-cog w-5"></i>
                        <span>Admin</span>
                    </a>
                </div>
            </div>

            <!-- User Info (Petugas/Admin yang sedang login) -->
            <div class="p-4 border-t border-blue-700 bg-blue-800">
                @php($petugas = auth('petugas')->user())
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $petugas->username ?? 'Admin' }}</p>
                        <p class="text-xs text-blue-200 truncate">{{ $petugas->email ?? '' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-blue-200 hover:text-white transition-colors duration-200">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-transition" :class="{ 'lg:ml-64': sidebarOpen }">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:block text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h2>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-6">
                <!-- Global Success Message -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2 text-lg"></i>
                            <span class="block sm:inline font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Global Error Message -->
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2 text-lg"></i>
                            <span class="block sm:inline font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div x-show="mobileSidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
             @click="mobileSidebarOpen = false"></div>
    </div>

    <!-- Scripts -->
    <script>
        // Auto-hide sidebar on mobile
        if (window.innerWidth < 1024) {
            document.querySelector('[x-data]').__x.$data.sidebarOpen = false;
        }
    </script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>
