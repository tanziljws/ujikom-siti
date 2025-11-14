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
use App\Http\Controllers\FotoController;
use App\Models\User;

// -------------------------------
// PUBLIC ROUTES (Tanpa Login)
// -------------------------------
Route::get('/', function () {
    // Get latest 5 galleries
    $latestGalleries = \App\Models\galery::with(['post.kategori', 'fotos'])
        ->where('status', 'aktif')
        ->orderBy(
            \App\Models\Post::select('created_at')
                ->whereColumn('posts.id', 'galery.post_id')
        , 'desc')
        ->limit(5)
        ->get();
    
    // Get latest 4 agendas
    $latestAgendas = \App\Models\Agenda::where('status', 'aktif')
        ->orderBy('order')
        ->limit(4)
        ->get();
    
    return view('user.dashboard', compact('latestGalleries', 'latestAgendas'));
})->name('user.dashboard');

Route::get('/user/gallery', function () {
    $galeri = \App\Models\galery::with(['post.kategori', 'fotos'])->where('status', 'aktif')->get();
    $kategoris = \App\Models\Kategori::orderBy('judul', 'asc')->get();
    return view('user.gallery', compact('galeri', 'kategoris'));
})->name('user.gallery');

Route::get('/user/agenda', function () {
    $agendaItems = \App\Models\Agenda::where('status', 'aktif')->orderBy('order')->get();
    return view('user.agenda', compact('agendaItems'));
})->name('user.agenda');

Route::get('/user/informasi', function () {
    $informasiItems = \App\Models\Informasi::where('status', 'aktif')
        ->orderBy('order')
        ->limit(5)
        ->get();
    return view('user.informasi', compact('informasiItems'));
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
    
    // ✅ Resource CRUD routes
    Route::resource('kategori', KategoriController::class);
    Route::resource('galeri', GaleriController::class);
    Route::resource('petugas', PetugasController::class);
    
    // Informasi - Edit only (school identity)
    Route::get('informasi', [\App\Http\Controllers\InformasiController::class, 'index'])->name('informasi.index');
    Route::put('informasi', [\App\Http\Controllers\InformasiController::class, 'update'])->name('informasi.update');
    
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
