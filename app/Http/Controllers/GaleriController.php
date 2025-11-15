<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\galery;
use App\Models\Kategori;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        try {
            $galeri = collect([]);
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('galery')) {
                    $galeri = galery::with(['post.kategori', 'fotos'])->get();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading galeri: ' . $e->getMessage());
                $galeri = collect([]);
            }

            if ($request->ajax()) {
                return response()->json($galeri);
            }

            return view('galeri.index', compact('galeri'));
        } catch (\Throwable $e) {
            \Log::error('GaleriController index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Error loading galleries'], 500);
            }
            
            return view('galeri.index', ['galeri' => collect([])]);
        }
    }

    public function create()
    {
        try {
            $kategori = collect([]);
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('kategori')) {
                    $kategori = Kategori::all();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading kategori: ' . $e->getMessage());
                $kategori = collect([]);
            }
            
            return view('galeri.create', compact('kategori'));
        } catch (\Throwable $e) {
            \Log::error('GaleriController create error: ' . $e->getMessage());
            return view('galeri.create', ['kategori' => collect([])]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori,id',
            'fotos' => 'required|array|min:1',
            'fotos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        // Buat folder upload jika belum ada
        if (!file_exists(public_path('uploads/galeri'))) {
            mkdir(public_path('uploads/galeri'), 0755, true);
        }

        // 1. Buat post dulu dengan cara yang lebih aman
        $post = new Post();
        $post->judul = $request->judul;
        $post->kategori_id = $request->kategori_id;
        
        // Tentukan petugas_id dengan benar
        if (auth('petugas')->check()) {
            // Jika login sebagai petugas
            $post->petugas_id = auth('petugas')->user()->id;
        } else if (Auth::check()) {
            // Jika login sebagai user biasa
            $post->petugas_id = Auth::user()->id;
        } else {
            // Jika tidak login sama sekali, gunakan ID default 1
            // dan log error untuk debugging
            Log::error('User not authenticated when creating post', [
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'petugas_id' => auth('petugas')->check() ? auth('petugas')->user()->id : null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            $post->petugas_id = 1;
        }
        
        $post->isi = $request->deskripsi ?? '';
        $post->status = 'published';
        $post->save();

        // Logging untuk memastikan post tersimpan
        Log::info('Post created for gallery and saved permanently', [
            'post_id' => $post->id,
            'title' => $post->judul,
            'created_at' => $post->created_at->format('Y-m-d H:i:s')
        ]);

        // 2. Buat galeri yang terkait dengan post
        $galeri = new Galery();
        $galeri->post_id = $post->id;
        $galeri->position = null;
        $galeri->status = 'aktif';
        $galeri->save();

        // Logging untuk memastikan galeri tersimpan
        Log::info('Gallery created and saved permanently', [
            'gallery_id' => $galeri->id,
            'post_id' => $galeri->post_id,
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);

        // 3. Upload dan simpan multiple files
        $uploadedFiles = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $index => $foto) {
                $namaFoto = time() . '_' . $index . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/galeri'), $namaFoto);
                
                // Simpan ke tabel foto dengan cara yang lebih aman
                $fotoRecord = new Foto();
                $fotoRecord->galery_id = $galeri->id;
                $fotoRecord->file = $namaFoto;
                $fotoRecord->save();
                
                // Logging untuk memastikan foto tersimpan
                Log::info('Photo uploaded for gallery and saved permanently', [
                    'photo_id' => $fotoRecord->id,
                    'gallery_id' => $galeri->id,
                    'filename' => $namaFoto,
                    'uploaded_at' => now()->format('Y-m-d H:i:s')
                ]);
                
                $uploadedFiles[] = $namaFoto;
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'data' => $galeri,
                'uploaded_files' => $uploadedFiles,
                'message' => count($uploadedFiles) . ' foto berhasil diupload dan akan tersimpan permanen!'
            ]);
        }

        return redirect()->route('galeri.index')->with('success', count($uploadedFiles) . ' foto berhasil ditambahkan ke galeri dan akan tersimpan permanen!');
    }

    public function show(galery $galeri)
    {
        try {
            $galeri->load(['post.kategori', 'fotos']);
            
            if (request()->ajax()) {
                return response()->json($galeri);
            }
            
            return view('galeri.show', compact('galeri'));
        } catch (\Throwable $e) {
            \Log::error('GaleriController show error: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json(['error' => 'Error loading gallery'], 500);
            }
            
            return redirect()->route('galeri.index')->with('error', 'Galeri tidak ditemukan');
        }
    }

    public function edit(galery $galeri)
    {
        try {
            $kategori = collect([]);
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('kategori')) {
                    $kategori = Kategori::all();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading kategori in edit: ' . $e->getMessage());
            }
            
            $galeri->load(['post.kategori', 'fotos']);

            // Jika AJAX, kembalikan data JSON untuk modal edit
            if (request()->ajax()) {
                return response()->json($galeri);
            }

            return view('galeri.edit', compact('galeri', 'kategori'));
        } catch (\Throwable $e) {
            \Log::error('GaleriController edit error: ' . $e->getMessage());
            return redirect()->route('galeri.index')->with('error', 'Error loading gallery');
        }
    }

    public function update(Request $request, galery $galeri)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori,id',
            'status' => 'required|in:aktif,nonaktif',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        // Update galeri status
        $galeri->update([
            'status' => $request->status
        ]);

        // Update post data
        if ($galeri->post) {
            $galeri->post->update([
                'judul' => $request->judul,
                'isi' => $request->deskripsi,
                'kategori_id' => $request->kategori_id,
            ]);
        }

        // Handle new photo uploads
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $namaFoto = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/galeri'), $namaFoto);
                
                // Create foto record
                $galeri->fotos()->create([
                    'file' => $namaFoto,
                    'galeri_id' => $galeri->id
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $galeri->load(['post.kategori', 'fotos'])]);
        }

        return redirect()->route('galeri.index')->with('success', 'Galeri berhasil diupdate!');
    }

    public function destroy(galery $galeri)
    {
        // Logging sebelum menghapus
        Log::info('Gallery deletion started', [
            'gallery_id' => $galeri->id,
            'post_id' => $galeri->post_id,
            'started_at' => now()->format('Y-m-d H:i:s')
        ]);

        // Perbaiki penghapusan foto
        foreach ($galeri->fotos as $foto) {
            $filePath = public_path('uploads/galeri/' . $foto->file);
            if (file_exists($filePath)) {
                unlink($filePath);
                
                // Logging penghapusan file
                Log::info('Photo file deleted', [
                    'photo_id' => $foto->id,
                    'filename' => $foto->file,
                    'deleted_at' => now()->format('Y-m-d H:i:s')
                ]);
            }
            $foto->delete();
        }

        // Hapus galeri
        $galeri->delete();

        // Hapus post terkait
        if ($galeri->post) {
            $galeri->post->delete();
            
            // Logging penghapusan post
            Log::info('Post deleted for gallery', [
                'post_id' => $galeri->post->id,
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ]);
        }

        // Logging selesai menghapus
        Log::info('Gallery deletion completed', [
            'gallery_id' => $galeri->id,
            'completed_at' => now()->format('Y-m-d H:i:s')
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('galeri.index')->with('success', 'Galeri berhasil dihapus!');
    }

    // Tambahan: toggle status (aktif/nonaktif atau verified/pending)
    public function toggleStatus(galery $galeri)
    {
        $galeri->status = $galeri->status === 'aktif' ? 'nonaktif' : 'aktif';
        $galeri->save();

        return response()->json(['success' => true, 'status' => $galeri->status]);
    }
}
