<?php

namespace App\Http\Controllers;

use App\Models\GalleryLikeLog;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryLikeLogController extends Controller
{
    /**
     * Display a listing of the gallery like/dislike logs.
     */
    public function index(Request $request)
    {
        try {
            $logs = collect([]);
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('gallery_like_logs')) {
                    $logs = GalleryLikeLog::with(['user', 'foto.galery.post'])
                        ->orderByDesc('created_at')
                        ->paginate(20);
                }
            } catch (\Exception $e) {
                \Log::error('Error loading gallery like logs: ' . $e->getMessage());
                $logs = collect([]);
            }

            return view('admin.reports.gallery-like-logs', compact('logs'));
        } catch (\Throwable $e) {
            \Log::error('GalleryLikeLogController index error: ' . $e->getMessage());
            return view('admin.reports.gallery-like-logs', ['logs' => collect([])]);
        }
    }

    /**
     * Reset semua data like/dislike (hanya admin/petugas).
     * - Set kolom likes & dislikes di tabel foto menjadi 0.
     * - Hapus seluruh data di tabel user_likes dan user_dislikes.
     * - Kosongkan riwayat gallery_like_logs.
     */
    public function resetAll(Request $request)
    {
        // Reset counter di tabel foto
        Foto::query()->update(['likes' => 0, 'dislikes' => 0]);

        // Hapus semua relasi like/dislike per user
        DB::table('user_likes')->delete();
        DB::table('user_dislikes')->delete();

        // Kosongkan riwayat log
        GalleryLikeLog::truncate();

        return redirect()
            ->route('galeri.like-logs')
            ->with('status', 'Semua data like/dislike berhasil direset.');
    }
}
