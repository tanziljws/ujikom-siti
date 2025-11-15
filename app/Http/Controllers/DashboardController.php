<?php

namespace App\Http\Controllers;

use App\Models\galery;
use App\Models\Kategori;
use App\Models\Petugas;
use App\Models\Page;
use App\Models\User;
use App\Models\Agenda;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Initialize variables with defaults
            $totalGaleri = 0;
            $galeriAktif = 0;
            $totalKategori = 0;
            $totalPetugas = 0;
            $totalPages = 0;
            $totalUsers = 0;
            $recentGaleri = collect([]);
            $pendingGaleri = collect([]);
            $recentAgenda = collect([]);
            $recentActivities = collect([]);
            
            // Get real statistics with error handling
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('galery')) {
                    $totalGaleri = galery::count();
                    $galeriAktif = galery::where('status', 'aktif')->count();
                }
            } catch (\Exception $e) {
                \Log::error('Error counting galeri: ' . $e->getMessage());
            }
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('kategori')) {
                    $totalKategori = Kategori::count();
                }
            } catch (\Exception $e) {
                \Log::error('Error counting kategori: ' . $e->getMessage());
            }
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('petugas')) {
                    $totalPetugas = Petugas::count();
                }
            } catch (\Exception $e) {
                \Log::error('Error counting petugas: ' . $e->getMessage());
            }
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('pages')) {
                    $totalPages = Page::count();
                }
            } catch (\Exception $e) {
                \Log::error('Error counting pages: ' . $e->getMessage());
            }
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                    $totalUsers = User::count();
                }
            } catch (\Exception $e) {
                \Log::error('Error counting users: ' . $e->getMessage());
            }
            
            // Get recent galeri (last 5) - order by posts created_at
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('galery') && \Illuminate\Support\Facades\Schema::hasTable('posts')) {
                    $recentGaleri = galery::with(['post.kategori', 'fotos'])
                        ->join('posts', 'galery.post_id', '=', 'posts.id')
                        ->orderBy('posts.created_at', 'desc')
                        ->select('galery.*')
                        ->limit(5)
                        ->get();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading recent galeri: ' . $e->getMessage());
                $recentGaleri = collect([]);
            }
            
            // Get galeri pending approval (if any) - order by posts created_at
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('galery') && \Illuminate\Support\Facades\Schema::hasTable('posts')) {
                    $pendingGaleri = galery::where('galery.status', 'nonaktif')
                        ->with(['post.kategori', 'fotos'])
                        ->join('posts', 'galery.post_id', '=', 'posts.id')
                        ->orderBy('posts.created_at', 'desc')
                        ->select('galery.*')
                        ->limit(5)
                        ->get();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading pending galeri: ' . $e->getMessage());
                $pendingGaleri = collect([]);
            }
            
            // Get recent agenda (last 5) - order by created_at
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('agenda')) {
                    $recentAgenda = Agenda::where('status', 'aktif')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading recent agenda: ' . $e->getMessage());
                $recentAgenda = collect([]);
            }
            
            // Get recent activities (last 10)
            $recentActivities = collect();
            
            // Add recent galeri as activities
            try {
                foreach ($recentGaleri as $galeri) {
                    $recentActivities->push([
                        'type' => 'galeri',
                        'title' => $galeri->post->judul ?? 'Galeri tanpa judul',
                        'description' => 'Galeri baru ditambahkan',
                        'time' => $galeri->post->created_at ?? now(),
                        'icon' => 'fas fa-images',
                        'color' => 'text-blue-600'
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Error building activities: ' . $e->getMessage());
            }
            
            // Sort by time
            $recentActivities = $recentActivities->sortByDesc('time')->take(10);
            
            // Calculate growth (mock data for now)
            $galeriGrowth = $totalGaleri > 0 ? '+12%' : '0%';
            $visitorGrowth = '+5%';
            $categoryGrowth = '+8%';
            $pageGrowth = '+3%';
            
            return view('dashboard', compact(
                'totalGaleri',
                'galeriAktif',
                'totalKategori',
                'totalPetugas',
                'totalPages',
                'totalUsers',
                'recentGaleri',
                'pendingGaleri',
                'recentAgenda',
                'recentActivities',
                'galeriGrowth',
                'visitorGrowth',
                'categoryGrowth',
                'pageGrowth'
            ));
        } catch (\Throwable $e) {
            \Log::error('Dashboard error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            // Return dashboard with empty data instead of error
            return view('dashboard', [
                'totalGaleri' => 0,
                'galeriAktif' => 0,
                'totalKategori' => 0,
                'totalPetugas' => 0,
                'totalPages' => 0,
                'totalUsers' => 0,
                'recentGaleri' => collect([]),
                'pendingGaleri' => collect([]),
                'recentAgenda' => collect([]),
                'recentActivities' => collect([]),
                'galeriGrowth' => '0%',
                'visitorGrowth' => '0%',
                'categoryGrowth' => '0%',
                'pageGrowth' => '0%',
            ]);
        }
    }
}