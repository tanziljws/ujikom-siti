<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
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
// Debug route untuk test homepage dengan detail lengkap
Route::get('/test-homepage-full', function () {
    $results = [];
    
    try {
        // Test 1: Check semua tabel
        $results['tables'] = [
            'galery' => Schema::hasTable('galery'),
            'posts' => Schema::hasTable('posts'),
            'agenda' => Schema::hasTable('agenda'),
            'site_settings' => Schema::hasTable('site_settings'),
        ];
        
        // Test 2: Query galleries
        try {
            $latestGaleriIds = \App\Models\galery::join('posts', 'galery.post_id', '=', 'posts.id')
                ->where('galery.status', 'aktif')
                ->orderBy('posts.created_at', 'desc')
                ->select('galery.id')
                ->limit(5)
                ->pluck('id');
            
            $results['gallery_ids'] = $latestGaleriIds->toArray();
            
            if ($latestGaleriIds->isNotEmpty()) {
                $latestGalleries = \App\Models\galery::with(['post.kategori', 'fotos'])
                    ->whereIn('id', $latestGaleriIds)
                    ->get()
                    ->filter(function($gallery) {
                        return $gallery->post !== null;
                    })
                    ->sortByDesc(function($gallery) {
                        return $gallery->post->created_at ?? now();
                    })
                    ->values();
                
                $results['galleries'] = [
                    'count' => $latestGalleries->count(),
                    'sample' => $latestGalleries->first() ? [
                        'id' => $latestGalleries->first()->id,
                        'has_post' => $latestGalleries->first()->post !== null,
                        'post_title' => $latestGalleries->first()->post->judul ?? null,
                    ] : null,
                ];
            } else {
                $results['galleries'] = 'No galleries found';
            }
        } catch (\Exception $e) {
            $results['galleries_error'] = $e->getMessage();
        }
        
        // Test 3: Query agendas
        try {
            if (Schema::hasTable('agenda')) {
                $latestAgendas = \App\Models\Agenda::where('status', 'aktif')
                    ->orderBy('order')
                    ->limit(4)
                    ->get();
                $results['agendas'] = [
                    'count' => $latestAgendas->count(),
                ];
            } else {
                $results['agendas'] = 'Table does not exist';
            }
        } catch (\Exception $e) {
            $results['agendas_error'] = $e->getMessage();
        }
        
        // Test 4: SiteSetting
        try {
            $testSetting = \App\Models\SiteSetting::get('home_hero_title', 'default');
            $results['site_setting'] = 'OK - ' . $testSetting;
        } catch (\Exception $e) {
            $results['site_setting_error'] = $e->getMessage();
        }
        
        // Test 5: Render view dengan empty data
        try {
            $latestGalleries = collect([]);
            $latestAgendas = collect([]);
            $view = view('user.dashboard', compact('latestGalleries', 'latestAgendas'));
            $results['view_empty'] = 'OK';
        } catch (\Exception $e) {
            $results['view_empty_error'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
        
        // Test 6: Simulasi route / yang sebenarnya
        try {
            $latestGalleries = collect([]);
            $latestAgendas = collect([]);
            
            if (Schema::hasTable('galery') && Schema::hasTable('posts')) {
                $latestGaleriIds = \App\Models\galery::join('posts', 'galery.post_id', '=', 'posts.id')
                    ->where('galery.status', 'aktif')
                    ->orderBy('posts.created_at', 'desc')
                    ->select('galery.id')
                    ->limit(5)
                    ->pluck('id');
                
                if ($latestGaleriIds->isNotEmpty()) {
                    $latestGalleries = \App\Models\galery::with(['post.kategori', 'fotos'])
                        ->whereIn('id', $latestGaleriIds)
                        ->get()
                        ->filter(function($gallery) {
                            return $gallery->post !== null;
                        })
                        ->sortByDesc(function($gallery) {
                            return $gallery->post->created_at ?? now();
                        })
                        ->values();
                }
            }
            
            if (Schema::hasTable('agenda')) {
                $latestAgendas = \App\Models\Agenda::where('status', 'aktif')
                    ->orderBy('order')
                    ->limit(4)
                    ->get();
            }
            
            $view = view('user.dashboard', compact('latestGalleries', 'latestAgendas'));
            $results['view_with_real_data'] = 'OK';
        } catch (\Exception $e) {
            $results['view_with_real_data_error'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
        }
        
    } catch (\Exception $e) {
        $results['general_error'] = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

// Debug route untuk test homepage query
Route::get('/test-homepage', function () {
    $results = [];
    
    try {
        // Test 1: Check tables
        $results['tables'] = [
            'galery' => Schema::hasTable('galery'),
            'posts' => Schema::hasTable('posts'),
            'agenda' => Schema::hasTable('agenda'),
        ];
        
        // Test 2: Query galleries
        try {
            $latestGaleriIds = \App\Models\galery::join('posts', 'galery.post_id', '=', 'posts.id')
                ->where('galery.status', 'aktif')
                ->orderBy('posts.created_at', 'desc')
                ->select('galery.id')
                ->limit(5)
                ->pluck('id');
            
            $results['gallery_ids'] = $latestGaleriIds->toArray();
            
            if ($latestGaleriIds->isNotEmpty()) {
                $latestGalleries = \App\Models\galery::with(['post.kategori', 'fotos'])
                    ->whereIn('id', $latestGaleriIds)
                    ->get();
                
                $results['galleries'] = [
                    'count' => $latestGalleries->count(),
                    'data' => $latestGalleries->map(function($g) {
                        return [
                            'id' => $g->id,
                            'has_post' => $g->post !== null,
                            'post_title' => $g->post->judul ?? null,
                            'fotos_count' => $g->fotos ? $g->fotos->count() : 0,
                        ];
                    })->toArray(),
                ];
            } else {
                $results['galleries'] = 'No galleries found';
            }
        } catch (\Exception $e) {
            $results['galleries_error'] = $e->getMessage();
        }
        
        // Test 3: Query agendas
        try {
            $latestAgendas = \App\Models\Agenda::where('status', 'aktif')
                ->orderBy('order')
                ->limit(4)
                ->get();
            $results['agendas'] = [
                'count' => $latestAgendas->count(),
                'data' => $latestAgendas->toArray(),
            ];
        } catch (\Exception $e) {
            $results['agendas_error'] = $e->getMessage();
        }
        
        // Test 4: Try to render view
        try {
            $latestGalleries = collect([]);
            $latestAgendas = collect([]);
            $view = view('user.dashboard', compact('latestGalleries', 'latestAgendas'));
            $results['view'] = 'OK - View can be rendered';
        } catch (\Exception $e) {
            $results['view_error'] = $e->getMessage();
        }
        
    } catch (\Exception $e) {
        $results['general_error'] = $e->getMessage();
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

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
        // Get latest 5 galleries - load relasi dengan benar
        $latestGalleries = collect([]);
        try {
            // Cek apakah tabel ada
            if (Schema::hasTable('galery') && Schema::hasTable('posts')) {
                // Ambil ID galeri terbaru dulu
                $latestGaleriIds = \App\Models\galery::join('posts', 'galery.post_id', '=', 'posts.id')
                    ->where('galery.status', 'aktif')
                    ->orderBy('posts.created_at', 'desc')
                    ->select('galery.id')
                    ->limit(5)
                    ->pluck('id');
                
                // Load dengan relasi
                if ($latestGaleriIds->isNotEmpty()) {
                    $latestGalleries = \App\Models\galery::with(['post.kategori', 'fotos'])
                        ->whereIn('id', $latestGaleriIds)
                        ->get()
                        ->filter(function($gallery) {
                            return $gallery->post !== null;
                        })
                        ->sortByDesc(function($gallery) {
                            return $gallery->post->created_at ?? now();
                        })
                        ->values();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error loading galleries: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            // Jika error, gunakan empty collection
            $latestGalleries = collect([]);
        }
        
        // Get latest 4 agendas - dengan error handling
        $latestAgendas = collect([]);
        try {
            // Cek apakah tabel agenda ada
            if (Schema::hasTable('agenda')) {
                try {
                    $latestAgendas = \App\Models\Agenda::where('status', 'aktif')
                        ->orderBy('order')
                        ->limit(4)
                        ->get();
                } catch (\Exception $e) {
                    \Log::warning('Error querying agendas (table exists but query failed): ' . $e->getMessage());
                    $latestAgendas = collect([]);
                }
            } else {
                // Tabel tidak ada, skip saja (mungkin migration belum jalan)
                \Log::info('Table agenda does not exist, skipping agenda query');
                $latestAgendas = collect([]);
            }
        } catch (\Exception $e) {
            \Log::error('Error loading agendas: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            // Jika error, gunakan empty collection
            $latestAgendas = collect([]);
        }
        
        // Render view dengan error handling
        try {
            // Pastikan variables ada dan collection
            if (!isset($latestGalleries) || !is_object($latestGalleries)) {
                $latestGalleries = collect([]);
            }
            if (!isset($latestAgendas) || !is_object($latestAgendas)) {
                $latestAgendas = collect([]);
            }
            
            // Pre-load SiteSetting untuk menghindari error di view
            try {
                if (Schema::hasTable('site_settings')) {
                    // Pre-load settings jika diperlukan
                    \App\Models\SiteSetting::all();
                }
            } catch (\Exception $e) {
                \Log::warning('SiteSetting table error (non-fatal): ' . $e->getMessage());
            }
            
            return view('user.dashboard', compact('latestGalleries', 'latestAgendas'));
        } catch (\Throwable $e) {
            \Log::error('Error rendering view: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'type' => get_class($e),
            ]);
            throw $e;
        }
    } catch (\Throwable $e) {
        // Jika database error, tampilkan halaman error atau fallback
        \Log::error('Error loading homepage: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'type' => get_class($e),
        ]);
        
        // Return JSON untuk debugging jika request expects JSON
        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'error' => 'Error loading homepage',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
        
        // Coba render error view, jika gagal return plain text
        try {
            return response()->view('errors.database', [
                'message' => config('app.debug') ? $e->getMessage() : 'Database connection error. Please check your configuration.'
            ], 500);
        } catch (\Exception $viewError) {
            // Jika error view juga gagal, return JSON dengan detail error
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'type' => get_class($e),
                'view_error' => $viewError->getMessage(),
            ], 500);
        }
    }
})->name('user.dashboard');

Route::get('/user/gallery', function () {
    try {
        $galeri = collect([]);
        $kategoris = collect([]);
        
        // Query galleries dengan error handling
        try {
            if (Schema::hasTable('galery') && Schema::hasTable('posts')) {
                $galeri = \App\Models\galery::with(['post.kategori', 'fotos'])
                    ->where('status', 'aktif')
                    ->get()
                    ->filter(function($gallery) {
                        return $gallery->post !== null;
                    })
                    ->values();
            }
        } catch (\Exception $e) {
            \Log::error('Error loading galleries: ' . $e->getMessage());
            $galeri = collect([]);
        }
        
        // Query kategori dengan error handling
        try {
            if (Schema::hasTable('kategori')) {
                $kategoris = \App\Models\Kategori::orderBy('judul', 'asc')->get();
            }
        } catch (\Exception $e) {
            \Log::error('Error loading kategori: ' . $e->getMessage());
            $kategoris = collect([]);
        }
        
        return view('user.gallery', compact('galeri', 'kategoris'));
    } catch (\Throwable $e) {
        \Log::error('Error loading gallery page: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        return response()->view('errors.database', [
            'message' => config('app.debug') ? $e->getMessage() : 'Error loading gallery page.'
        ], 500);
    }
})->name('user.gallery');

Route::get('/user/agenda', function () {
    try {
        $agendaItems = collect([]);
        
        // Query agenda dengan error handling
        try {
            if (Schema::hasTable('agenda')) {
                $agendaItems = \App\Models\Agenda::where('status', 'aktif')
                    ->orderBy('order')
                    ->get();
            } else {
                \Log::info('Table agenda does not exist, using empty collection');
            }
        } catch (\Exception $e) {
            \Log::error('Error loading agenda: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            $agendaItems = collect([]);
        }
        
        return view('user.agenda', compact('agendaItems'));
    } catch (\Throwable $e) {
        \Log::error('Error loading agenda page: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        return response()->view('errors.database', [
            'message' => config('app.debug') ? $e->getMessage() : 'Error loading agenda page.'
        ], 500);
    }
})->name('user.agenda');

Route::get('/user/informasi', function () {
    try {
        $informasiItems = collect([]);
        
        // Query informasi dengan error handling
        try {
            if (Schema::hasTable('informasi')) {
                $informasiItems = \App\Models\Informasi::where('status', 'aktif')
                    ->orderByDesc('date') // paling baru dulu
                    ->orderBy('order')    // kalau tanggal sama, pakai urutan
                    ->limit(6)            // hanya tampilkan 6 informasi terbaru
                    ->get();
            } else {
                \Log::info('Table informasi does not exist, using empty collection');
            }
        } catch (\Exception $e) {
            \Log::error('Error loading informasi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            $informasiItems = collect([]);
        }
        
        return view('user.informasi', compact('informasiItems'));
    } catch (\Throwable $e) {
        \Log::error('Error loading informasi page: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        return response()->view('errors.database', [
            'message' => config('app.debug') ? $e->getMessage() : 'Error loading informasi page.'
        ], 500);
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
