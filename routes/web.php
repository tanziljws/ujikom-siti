<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\GalleryReportController;
use App\Http\Controllers\GalleryLikeLogController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\Admin\InformasiAdminController;
use App\Models\User;

// Load debug routes if in debug mode
if (config('app.debug')) {
    require __DIR__.'/debug.php';
}

// -------------------------------
// PUBLIC ROUTES (Tanpa Login)
// -------------------------------
// Debug route untuk test database connection (hapus setelah fix)
Route::get('/test-db', function () {
    try {
        \DB::connection()->getPdo();
        return response()->json([
            'status' => 'success',
            'message' => 'Database connection successful',
            'connection' => config('database.default'),
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'database' => config('database.connections.mysql.database'),
            'username' => config('database.connections.mysql.username'),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'connection' => config('database.default'),
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'database' => config('database.connections.mysql.database'),
            'username' => config('database.connections.mysql.username'),
            'env_db_connection' => env('DB_CONNECTION'),
            'env_db_host' => env('DB_HOST'),
            'env_db_port' => env('DB_PORT'),
        ], 500);
    }
});

Route::get('/', function () {
    try {
        // Get latest 5 galleries - menggunakan JOIN seperti di DashboardController
        $latestGalleries = collect([]);
        try {
            $latestGalleries = \App\Models\galery::with(['post.kategori', 'fotos'])
                ->join('posts', 'galery.post_id', '=', 'posts.id')
                ->where('galery.status', 'aktif')
                ->orderBy('posts.created_at', 'desc')
                ->select('galery.*')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error loading galleries: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            // Jika error, gunakan empty collection
            $latestGalleries = collect([]);
        }
        
        // Get latest 4 agendas - dengan error handling
        $latestAgendas = collect([]);
        try {
            $latestAgendas = \App\Models\Agenda::where('status', 'aktif')
                ->orderBy('order')
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error loading agendas: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            // Jika error, gunakan empty collection
            $latestAgendas = collect([]);
        }
        
        return view('user.dashboard', compact('latestGalleries', 'latestAgendas'));
    } catch (\Exception $e) {
        // Jika database error, tampilkan halaman error atau fallback
        \Log::error('Error loading homepage: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        
        // Return JSON untuk debugging jika request expects JSON
        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'error' => 'Error loading homepage',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
        
        return response()->view('errors.database', [
            'message' => config('app.debug') ? $e->getMessage() : 'Database connection error. Please check your configuration.'
        ], 500);
    }
})->name('user.dashboard');

Route::get('/user/gallery', function () {
    try {
        $galeri = \App\Models\galery::with(['post.kategori', 'fotos'])->where('status', 'aktif')->get();
        $kategoris = \App\Models\Kategori::orderBy('judul', 'asc')->get();
        return view('user.gallery', compact('galeri', 'kategoris'));
    } catch (\Exception $e) {
        \Log::error('Error loading gallery: ' . $e->getMessage());
        return response()->view('errors.database', ['message' => $e->getMessage()], 500);
    }
})->name('user.gallery');

Route::get('/user/agenda', function () {
    try {
        $agendaItems = \App\Models\Agenda::where('status', 'aktif')->orderBy('order')->get();
        return view('user.agenda', compact('agendaItems'));
    } catch (\Exception $e) {
        \Log::error('Error loading agenda: ' . $e->getMessage());
        return response()->view('errors.database', ['message' => $e->getMessage()], 500);
    }
})->name('user.agenda');

Route::get('/user/informasi', function () {
    try {
        $informasiItems = \App\Models\Informasi::where('status', 'aktif')
            ->orderByDesc('date') // paling baru dulu
            ->orderBy('order')    // kalau tanggal sama, pakai urutan
            ->limit(6)            // hanya tampilkan 6 informasi terbaru
            ->get();
        return view('user.informasi', compact('informasiItems'));
    } catch (\Exception $e) {
        \Log::error('Error loading informasi: ' . $e->getMessage());
        return response()->view('errors.database', ['message' => $e->getMessage()], 500);
    }
})->name('user.informasi');

// Public route untuk menampilkan pages
Route::get('/page/{slug}', [PageController::class, 'showBySlug'])->name('page.show');

// Download routes with captcha
Route::post('/download/generate-captcha', [DownloadController::class, 'generateCaptcha'])->name('download.captcha');
Route::post('/download/verify', [DownloadController::class, 'verifyCaptcha'])->name('download.verify');
Route::get('/download/{token}', [DownloadController::class, 'download'])->name('download.file');

// Foto routes
Route::post('/foto/{id}/toggle-like', [FotoController::class, 'toggleLike'])->name('foto.toggle-like');
Route::post('/foto/{id}/toggle-dislike', [FotoController::class, 'toggleDislike'])->name('foto.toggle-dislike');

// -------------------------------
// AUTH ROUTES (Login/Logout)
// -------------------------------
Route::middleware(['guest'])->group(function () {
    // Halaman pilihan login (admin/user/register)
    Route::get('/auth/choose', function () {
        return view('auth.choose-login');
    })->name('login.choose');

    // Form login utama (dipakai untuk admin & user)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register user baru
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------------------
// ADMIN & PETUGAS ROUTES (Harus Login)
// -------------------------------
// -------------------------------
// ADMIN & PETUGAS ROUTES (Harus Login sebagai petugas/admin)
// -------------------------------
Route::middleware(['auth:petugas'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gallery Report Routes
    Route::get('galeri/report', [GalleryReportController::class, 'index'])->name('galeri.report');
    Route::get('galeri/report/pdf', [GalleryReportController::class, 'exportPdf'])->name('galeri.report.pdf');

    // Gallery Like/Dislike Logs (riwayat like/dislike user)
    Route::get('galeri/like-logs', [GalleryLikeLogController::class, 'index'])->name('galeri.like-logs');
    Route::post('galeri/like-logs/reset', [GalleryLikeLogController::class, 'resetAll'])->name('galeri.like-logs.reset');
    
    // ✅ Resource CRUD routes
    Route::resource('kategori', KategoriController::class);
    Route::resource('galeri', GaleriController::class);
    Route::resource('petugas', PetugasController::class);
    
    // Informasi - Edit only (school identity)
    Route::get('informasi', [\App\Http\Controllers\InformasiController::class, 'index'])->name('informasi.index');
    Route::put('informasi', [\App\Http\Controllers\InformasiController::class, 'update'])->name('informasi.update');
    
    // Informasi Terbaru - CRUD untuk tabel informasi (data yang tampil di halaman user)
    Route::resource('admin/informasi-items', InformasiAdminController::class)->names('admin.informasi-items');
    
    Route::resource('agenda', \App\Http\Controllers\AgendaController::class);
    // ✅ Toggle status routes
    Route::patch('galeri/{galeri}/toggle-status', [GaleriController::class, 'toggleStatus'])->name('galeri.toggleStatus');
    
    // Gallery Report Routes
    Route::get('galeri/report', [GalleryReportController::class, 'index'])->name('galeri.report');
    Route::get('galeri/report/pdf', [GalleryReportController::class, 'exportPdf'])->name('galeri.report.pdf');

    // Ganti route admin profile dari view langsung ke controller
    Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile');

    // **Tambahan route update profile admin**
    Route::put('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    
    // **Route update password admin**
    Route::put('/admin/password', [AdminProfileController::class, 'updatePassword'])->name('admin.password.update');
    
    // **Site Settings Routes**
    Route::prefix('admin/settings')->name('site-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SiteSettingController::class, 'index'])->name('index');
        Route::get('/group/{group}', [\App\Http\Controllers\SiteSettingController::class, 'showGroup'])->name('group');
        Route::get('/{setting}/edit', [\App\Http\Controllers\SiteSettingController::class, 'edit'])->name('edit');
        Route::put('/{setting}', [\App\Http\Controllers\SiteSettingController::class, 'update'])->name('update');
        Route::put('/', [\App\Http\Controllers\SiteSettingController::class, 'updateBulk'])->name('update.bulk');
    });

    // Daftar user terdaftar (read-only)
    Route::get('/admin/users', function () {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    })->name('admin.users.index');
});
