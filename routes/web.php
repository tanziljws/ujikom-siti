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

// Debug route untuk cek file vs database
Route::get('/test-files-vs-db', function () {
    $results = [];
    
    try {
        // 1. Cek file fisik di folder
        $filesPath = public_path('uploads/galeri');
        $physicalFiles = [];
        if (is_dir($filesPath)) {
            $files = glob($filesPath . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
            $physicalFiles = array_map('basename', $files);
            $results['physical_files'] = [
                'count' => count($physicalFiles),
                'files' => array_slice($physicalFiles, 0, 10), // Sample 10 file pertama
            ];
        } else {
            $results['physical_files'] = 'Directory not found';
        }
        
        // 2. Cek data di database
        $results['database'] = [];
        
        if (Schema::hasTable('foto')) {
            $dbFiles = \App\Models\Foto::pluck('file')->toArray();
            $results['database']['foto_count'] = count($dbFiles);
            $results['database']['foto_files'] = array_slice($dbFiles, 0, 10); // Sample 10 file pertama
            
            // 3. Compare: file yang ada di folder tapi tidak di database
            $filesNotInDb = array_diff($physicalFiles, $dbFiles);
            $results['files_not_in_db'] = [
                'count' => count($filesNotInDb),
                'files' => array_slice($filesNotInDb, 0, 10),
            ];
            
            // 4. Compare: file yang ada di database tapi tidak di folder
            $filesNotInFolder = array_diff($dbFiles, $physicalFiles);
            $results['files_not_in_folder'] = [
                'count' => count($filesNotInFolder),
                'files' => array_slice($filesNotInFolder, 0, 10),
            ];
        }
        
        // 5. Cek galeri dan posts
        if (Schema::hasTable('galery')) {
            $results['database']['galery_count'] = \App\Models\galery::count();
            $results['database']['galery_aktif'] = \App\Models\galery::where('status', 'aktif')->count();
        }
        
        if (Schema::hasTable('posts')) {
            $results['database']['posts_count'] = \App\Models\Post::count();
        }
        
        // 6. Cek relasi galery -> post -> foto
        if (Schema::hasTable('galery') && Schema::hasTable('posts') && Schema::hasTable('foto')) {
            $galeriWithFotos = \App\Models\galery::with(['post', 'fotos'])
                ->where('status', 'aktif')
                ->get()
                ->filter(function($g) {
                    return $g->post !== null && $g->fotos->count() > 0;
                });
            
            $results['galeri_with_fotos'] = [
                'count' => $galeriWithFotos->count(),
                'sample' => $galeriWithFotos->take(3)->map(function($g) {
                    return [
                        'id' => $g->id,
                        'post_title' => $g->post->judul ?? null,
                        'fotos_count' => $g->fotos->count(),
                        'foto_files' => $g->fotos->pluck('file')->toArray(),
                    ];
                })->toArray(),
            ];
        }
        
    } catch (\Exception $e) {
        $results['error'] = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

// Debug route untuk cek data di database
Route::get('/test-data', function () {
    $results = [];
    
    try {
        // Test 1: Cek semua tabel
        $results['tables'] = [
            'galery' => Schema::hasTable('galery'),
            'posts' => Schema::hasTable('posts'),
            'kategori' => Schema::hasTable('kategori'),
            'agenda' => Schema::hasTable('agenda'),
            'informasi' => Schema::hasTable('informasi'),
            'site_settings' => Schema::hasTable('site_settings'),
            'users' => Schema::hasTable('users'),
            'petugas' => Schema::hasTable('petugas'),
        ];
        
        // Test 2: Count data di setiap tabel
        $results['counts'] = [];
        
        if (Schema::hasTable('galery')) {
            $results['counts']['galery'] = \App\Models\galery::count();
            $results['counts']['galery_aktif'] = \App\Models\galery::where('status', 'aktif')->count();
        }
        
        if (Schema::hasTable('posts')) {
            $results['counts']['posts'] = \App\Models\Post::count();
        }
        
        if (Schema::hasTable('kategori')) {
            $results['counts']['kategori'] = \App\Models\Kategori::count();
        }
        
        if (Schema::hasTable('agenda')) {
            $results['counts']['agenda'] = \App\Models\Agenda::count();
            $results['counts']['agenda_aktif'] = \App\Models\Agenda::where('status', 'aktif')->count();
        }
        
        if (Schema::hasTable('informasi')) {
            $results['counts']['informasi'] = \App\Models\Informasi::count();
            $results['counts']['informasi_aktif'] = \App\Models\Informasi::where('status', 'aktif')->count();
        }
        
        if (Schema::hasTable('foto')) {
            $results['counts']['foto'] = \App\Models\Foto::count();
        }
        
        if (Schema::hasTable('users')) {
            $results['counts']['users'] = \App\Models\User::count();
        }
        
        if (Schema::hasTable('petugas')) {
            $results['counts']['petugas'] = \App\Models\Petugas::count();
        }
        
        // Test 3: Sample data
        $results['samples'] = [];
        
        if (Schema::hasTable('galery')) {
            $sampleGaleri = \App\Models\galery::with('post')->first();
            $results['samples']['galery'] = $sampleGaleri ? [
                'id' => $sampleGaleri->id,
                'status' => $sampleGaleri->status,
                'has_post' => $sampleGaleri->post !== null,
                'post_title' => $sampleGaleri->post->judul ?? null,
            ] : null;
        }
        
        if (Schema::hasTable('kategori')) {
            $sampleKategori = \App\Models\Kategori::first();
            $results['samples']['kategori'] = $sampleKategori ? [
                'id' => $sampleKategori->id,
                'judul' => $sampleKategori->judul,
            ] : null;
        }
        
        if (Schema::hasTable('agenda')) {
            $sampleAgenda = \App\Models\Agenda::first();
            $results['samples']['agenda'] = $sampleAgenda ? [
                'id' => $sampleAgenda->id,
                'title' => $sampleAgenda->title,
                'status' => $sampleAgenda->status,
            ] : null;
        }
        
        if (Schema::hasTable('informasi')) {
            $sampleInformasi = \App\Models\Informasi::first();
            $results['samples']['informasi'] = $sampleInformasi ? [
                'id' => $sampleInformasi->id,
                'title' => $sampleInformasi->title ?? 'N/A',
                'status' => $sampleInformasi->status,
            ] : null;
        }
        
        // Test 4: Query yang digunakan di route
        $results['route_queries'] = [];
        
        // Query gallery
        try {
            if (Schema::hasTable('galery') && Schema::hasTable('posts')) {
                $galeriCount = \App\Models\galery::with(['post.kategori', 'fotos'])
                    ->where('status', 'aktif')
                    ->get()
                    ->filter(function($gallery) {
                        return $gallery->post !== null;
                    })
                    ->count();
                $results['route_queries']['gallery_aktif'] = $galeriCount;
            }
        } catch (\Exception $e) {
            $results['route_queries']['gallery_error'] = $e->getMessage();
        }
        
        // Query agenda
        try {
            if (Schema::hasTable('agenda')) {
                $agendaCount = \App\Models\Agenda::where('status', 'aktif')->count();
                $results['route_queries']['agenda_aktif'] = $agendaCount;
            }
        } catch (\Exception $e) {
            $results['route_queries']['agenda_error'] = $e->getMessage();
        }
        
        // Query informasi
        try {
            if (Schema::hasTable('informasi')) {
                $informasiCount = \App\Models\Informasi::where('status', 'aktif')->count();
                $results['route_queries']['informasi_aktif'] = $informasiCount;
            }
        } catch (\Exception $e) {
            $results['route_queries']['informasi_error'] = $e->getMessage();
        }
        
    } catch (\Exception $e) {
        $results['error'] = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
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
            // Cek apakah tabel ada - wrap dengan try-catch karena bisa error jika DB connection bermasalah
            $hasGalery = false;
            $hasPosts = false;
            try {
                $hasGalery = Schema::hasTable('galery');
                $hasPosts = Schema::hasTable('posts');
            } catch (\Exception $schemaError) {
                \Log::warning('Schema check error: ' . $schemaError->getMessage());
                // Jika schema check error, skip query
            }
            
            if ($hasGalery && $hasPosts) {
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
                            return $gallery->post !== null && 
                                   $gallery->fotos && 
                                   $gallery->fotos->count() > 0;
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
            // Cek apakah tabel agenda ada - wrap dengan try-catch
            $hasAgenda = false;
            try {
                $hasAgenda = Schema::hasTable('agenda');
            } catch (\Exception $schemaError) {
                \Log::warning('Schema check error for agenda: ' . $schemaError->getMessage());
            }
            
            if ($hasAgenda) {
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
                $hasSiteSettings = false;
                try {
                    $hasSiteSettings = Schema::hasTable('site_settings');
                } catch (\Exception $schemaError) {
                    \Log::warning('Schema check error for site_settings: ' . $schemaError->getMessage());
                }
                
                if ($hasSiteSettings) {
                    // Pre-load settings jika diperlukan
                    \App\Models\SiteSetting::all();
                }
            } catch (\Exception $e) {
                \Log::warning('SiteSetting table error (non-fatal): ' . $e->getMessage());
            }
            
            // Debug: Log gallery count sebelum render
            \Log::info('Rendering homepage', [
                'gallery_count' => $latestGalleries->count(),
                'agenda_count' => $latestAgendas->count(),
            ]);
            
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
        
        // Jangan tampilkan error view, langsung return homepage dengan empty data
        // Ini lebih user-friendly daripada menampilkan error page
        \Log::error('Homepage error (rendering with empty data): ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        
        // Return homepage dengan empty collections
        $latestGalleries = collect([]);
        $latestAgendas = collect([]);
        
        try {
    return view('user.dashboard', compact('latestGalleries', 'latestAgendas'));
        } catch (\Exception $viewError) {
            // Jika view juga error, return simple HTML
            return response('
                <html>
                <head><title>SMKN 4 Bogor</title></head>
                <body style="font-family: Arial; text-align: center; padding: 50px;">
                    <h1>SMKN 4 Bogor</h1>
                    <p>Sedang dalam perbaikan. Silakan coba beberapa saat lagi.</p>
                    <a href="/">Refresh</a>
                </body>
                </html>
            ', 200);
        }
    }
})->name('user.dashboard');

Route::get('/user/gallery', function () {
    // Initialize variables
    $galeri = collect([]);
    $kategoris = collect([]);
    
    try {
        // Query galleries dengan error handling
        try {
            $hasGalery = false;
            $hasPosts = false;
            try {
                $hasGalery = Schema::hasTable('galery');
                $hasPosts = Schema::hasTable('posts');
            } catch (\Exception $schemaError) {
                \Log::warning('Schema check error in gallery: ' . $schemaError->getMessage());
            }
            
            if ($hasGalery && $hasPosts) {
                try {
                    // Query dengan error handling lebih detail
                    $galeri = \App\Models\galery::with(['post.kategori', 'fotos'])
                        ->where('status', 'aktif')
                        ->get();
                    
                    \Log::info('Gallery query result (before filter)', [
                        'count' => $galeri->count(),
                    ]);
                    
                    // Filter galleries yang punya post dan foto
                    $galeri = $galeri->filter(function($gallery) {
                        try {
                            $hasPost = $gallery->post !== null;
                            $hasFotos = $gallery->fotos && $gallery->fotos->count() > 0;
                            return $hasPost && $hasFotos;
                        } catch (\Exception $filterError) {
                            \Log::warning('Error filtering gallery ' . $gallery->id . ': ' . $filterError->getMessage());
                            return false;
                        }
                    });
                    
                    // Sort by created_at
                    $galeri = $galeri->sortByDesc(function($gallery) {
                        try {
                            return $gallery->post->created_at ?? now();
                        } catch (\Exception $e) {
                            return now();
                        }
                    })->values();
                    
                    // Debug: Log gallery count
                    \Log::info('Gallery page loaded (after filter)', [
                        'gallery_count' => $galeri->count(),
                        'sample_ids' => $galeri->take(5)->pluck('id')->toArray(),
                    ]);
                } catch (\Exception $queryError) {
                    \Log::error('Error querying galleries: ' . $queryError->getMessage(), [
                        'trace' => $queryError->getTraceAsString(),
                        'file' => $queryError->getFile(),
                        'line' => $queryError->getLine(),
                    ]);
                    $galeri = collect([]);
                }
            } else {
                \Log::warning('Gallery query skipped - tables missing', [
                    'has_galery' => $hasGalery,
                    'has_posts' => $hasPosts,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error loading galleries: ' . $e->getMessage());
            $galeri = collect([]);
        }
        
        // Query kategori dengan error handling
        try {
            $hasKategori = false;
            try {
                $hasKategori = Schema::hasTable('kategori');
            } catch (\Exception $schemaError) {
                \Log::warning('Schema check error for kategori: ' . $schemaError->getMessage());
            }
            
            if ($hasKategori) {
    $kategoris = \App\Models\Kategori::orderBy('judul', 'asc')->get();
            }
        } catch (\Exception $e) {
            \Log::error('Error loading kategori: ' . $e->getMessage());
            $kategoris = collect([]);
        }
        
        // Debug: Log sebelum render
        \Log::info('Rendering gallery page', [
            'gallery_count' => $galeri->count(),
            'kategori_count' => $kategoris->count(),
            'gallery_type' => get_class($galeri),
            'is_collection' => $galeri instanceof \Illuminate\Support\Collection,
        ]);
        
        // Pastikan $galeri adalah collection
        if (!($galeri instanceof \Illuminate\Support\Collection)) {
            \Log::warning('$galeri is not a collection, converting...', [
                'type' => gettype($galeri),
                'class' => get_class($galeri),
            ]);
            $galeri = collect($galeri);
        }
        
        return view('user.gallery', compact('galeri', 'kategoris'));
    } catch (\Throwable $e) {
        \Log::error('Error loading gallery page: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        // Jangan tampilkan error view, langsung render dengan empty data
        $galeri = collect([]);
        $kategoris = collect([]);
    return view('user.gallery', compact('galeri', 'kategoris'));
    }
})->name('user.gallery');

Route::get('/user/agenda', function () {
    try {
        $agendaItems = collect([]);
        
        // Query agenda dengan error handling
        try {
            $hasAgenda = false;
            try {
                $hasAgenda = Schema::hasTable('agenda');
            } catch (\Exception $schemaError) {
                \Log::warning('Schema check error for agenda: ' . $schemaError->getMessage());
            }
            
            if ($hasAgenda) {
                $agendaItems = \App\Models\Agenda::where('status', 'aktif')
                    ->orderBy('order')
                    ->get();
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
        // Jangan tampilkan error view, langsung render dengan empty data
        $agendaItems = collect([]);
    return view('user.agenda', compact('agendaItems'));
    }
})->name('user.agenda');

Route::get('/user/informasi', function () {
    try {
        $informasiItems = collect([]);
        
        // Query informasi dengan error handling
        try {
            $hasInformasi = false;
            try {
                $hasInformasi = Schema::hasTable('informasi');
            } catch (\Exception $schemaError) {
                \Log::warning('Schema check error for informasi: ' . $schemaError->getMessage());
            }
            
            if ($hasInformasi) {
    $informasiItems = \App\Models\Informasi::where('status', 'aktif')
        ->orderByDesc('date') // paling baru dulu
        ->orderBy('order')    // kalau tanggal sama, pakai urutan
        ->limit(6)            // hanya tampilkan 6 informasi terbaru
        ->get();
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
        // Jangan tampilkan error view, langsung render dengan empty data
        $informasiItems = collect([]);
    return view('user.informasi', compact('informasiItems'));
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
