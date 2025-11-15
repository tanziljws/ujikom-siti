<?php

namespace App\Http\Controllers;

use App\Models\Galery;
use App\Models\Kategori;
use App\Models\Foto;
use App\Models\Post;
use App\Models\Agenda;
use App\Models\User;
use App\Models\Informasi;
use App\Models\GalleryLikeLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GalleryReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Log that we've reached this method
            Log::info('GalleryReportController@index called', [
                'user_id' => Auth::id(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            // Get filter parameters
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $kategoriId = $request->input('kategori_id');
            $status = $request->input('status');

            // Build gallery query with error handling
            $galleryQuery = null;
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('galery')) {
                    $galleryQuery = Galery::with(['post.kategori', 'fotos']);
                } else {
                    $galleryQuery = Galery::query(); // Empty query
                }
            } catch (\Exception $e) {
                \Log::error('Error building gallery query: ' . $e->getMessage());
                $galleryQuery = Galery::query(); // Empty query
            }

            // Apply filters to gallery query
            if ($galleryQuery) {
                try {
                    if ($startDate && $endDate) {
                        $galleryQuery->whereHas('post', function($q) use ($startDate, $endDate) {
                            $q->whereBetween('created_at', [$startDate, $endDate]);
                        });
                    }

                    if ($kategoriId) {
                        $galleryQuery->whereHas('post', function($q) use ($kategoriId) {
                            $q->where('kategori_id', $kategoriId);
                        });
                    }

                    if ($status) {
                        $galleryQuery->where('status', $status);
                    }

                    $galeries = $galleryQuery->get();
                } catch (\Exception $e) {
                    \Log::error('Error applying filters: ' . $e->getMessage());
                    $galeries = collect([]);
                }
            } else {
                $galeries = collect([]);
            }

            // Get agenda statistics
            $agendas = collect([]);
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('agenda')) {
                    $agendaQuery = Agenda::query();
                    if ($startDate && $endDate) {
                        $agendaQuery->whereBetween('created_at', [$startDate, $endDate]);
                    }
                    if ($status) {
                        $agendaQuery->where('status', $status);
                    }
                    $agendas = $agendaQuery->get();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading agendas: ' . $e->getMessage());
            }

            // Calculate statistics
            try {
                $statistics = $this->calculateStatistics($galeries, $agendas);
            } catch (\Exception $e) {
                \Log::error('Error calculating statistics: ' . $e->getMessage());
                $statistics = [];
            }

            // Get all categories for filter
            $categories = collect([]);
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('kategori')) {
                    $categories = Kategori::all();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading categories: ' . $e->getMessage());
            }
            
            // Log before returning view
            Log::info('Returning view with data', [
                'galeries_count' => $galeries->count(),
                'agendas_count' => $agendas->count(),
                'categories_count' => $categories->count(),
                'user_id' => Auth::id()
            ]);

            return view('admin.reports.galeri', compact('galeries', 'agendas', 'statistics', 'categories', 'startDate', 'endDate', 'kategoriId', 'status'));
        } catch (\Throwable $e) {
            \Log::error('GalleryReportController index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return view('admin.reports.galeri', [
                'galeries' => collect([]),
                'agendas' => collect([]),
                'statistics' => [],
                'categories' => collect([]),
                'startDate' => null,
                'endDate' => null,
                'kategoriId' => null,
                'status' => null,
            ]);
        }
    }

    public function exportPdf(Request $request)
    {
        // Log that we've reached this method
        Log::info('GalleryReportController@exportPdf called', [
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
        // Get filter parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $kategoriId = $request->input('kategori_id');
        $status = $request->input('status');

        // Build gallery query
        $galleryQuery = Galery::with(['post.kategori', 'fotos']);

        // Apply filters to gallery query
        if ($startDate && $endDate) {
            $galleryQuery->whereHas('post', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }

        if ($kategoriId) {
            $galleryQuery->whereHas('post', function($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }

        if ($status) {
            $galleryQuery->where('status', $status);
        }

        $galeries = $galleryQuery->get();

        // Get agenda statistics
        $agendaQuery = Agenda::query();
        if ($startDate && $endDate) {
            $agendaQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($status) {
            $agendaQuery->where('status', $status);
        }
        $agendas = $agendaQuery->get();

        // Calculate statistics
        $statistics = $this->calculateStatistics($galeries, $agendas);

        // Get category name if filtered
        $categoryName = null;
        if ($kategoriId) {
            $category = Kategori::find($kategoriId);
            $categoryName = $category ? $category->judul : null;
        }

        // Prepare data for PDF
        $data = [
            'galeries' => $galeries,
            'agendas' => $agendas,
            'statistics' => $statistics,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'categoryName' => $categoryName,
            'status' => $status,
            'generatedDate' => Carbon::now()->format('d F Y H:i'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('admin.reports.galeri-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $filename = 'laporan-galeri-' . Carbon::now()->format('Y-m-d-His') . '.pdf';

        return $pdf->download($filename);
    }

    private function calculateStatistics($galeries, $agendas)
    {
        $totalGaleries = $galeries->count();
        $totalPhotos = 0;
        $totalAktifGalleries = 0;
        $totalNonaktifGalleries = 0;
        $categoriesCount = [];

        foreach ($galeries as $galeri) {
            // Count photos
            $totalPhotos += $galeri->fotos->count();

            // Count by status
            if ($galeri->status === 'aktif') {
                $totalAktifGalleries++;
            } else {
                $totalNonaktifGalleries++;
            }

            // Count by category
            if ($galeri->post && $galeri->post->kategori) {
                $categoryName = $galeri->post->kategori->judul;
                if (!isset($categoriesCount[$categoryName])) {
                    $categoriesCount[$categoryName] = 0;
                }
                $categoriesCount[$categoryName]++;
            }
        }

        // Agenda statistics
        $totalAgendas = $agendas->count();
        $totalAktifAgendas = $agendas->where('status', 'aktif')->count();
        $totalNonaktifAgendas = $agendas->where('status', 'nonaktif')->count();

        // System-wide statistics
        $totalUsers = User::count();
        $totalInformasi = Informasi::count();
        $totalLikes = Foto::sum('likes');
        $totalDislikes = Foto::sum('dislikes');
        $totalLikeLogs = GalleryLikeLog::count();

        // Sort categories by count
        arsort($categoriesCount);

        $mostPopularCategory = !empty($categoriesCount) ? array_key_first($categoriesCount) : null;

        return [
            'total_galeries' => $totalGaleries,
            'total_photos' => $totalPhotos,
            'total_agendas' => $totalAgendas,
            'total_aktif_galleries' => $totalAktifGalleries,
            'total_nonaktif_galleries' => $totalNonaktifGalleries,
            'total_aktif_agendas' => $totalAktifAgendas,
            'total_nonaktif_agendas' => $totalNonaktifAgendas,
            'avg_photos_per_gallery' => $totalGaleries > 0 ? round($totalPhotos / $totalGaleries, 2) : 0,
            'categories_count' => $categoriesCount,
            'most_popular_category' => $mostPopularCategory,
            'total_users' => $totalUsers,
            'total_informasi' => $totalInformasi,
            'total_likes' => $totalLikes,
            'total_dislikes' => $totalDislikes,
            'total_like_logs' => $totalLikeLogs,
        ];
    }
}