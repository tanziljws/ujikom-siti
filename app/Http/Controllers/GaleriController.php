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
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'kategori_id' => 'required|exists:kategori,id',
                'fotos' => 'required|array|min:1',
                'fotos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
            ]);

            // Buat folder upload jika belum ada
            try {
                if (!file_exists(public_path('uploads/galeri'))) {
                    mkdir(public_path('uploads/galeri'), 0755, true);
                }
            } catch (\Exception $e) {
                \Log::error('Error creating upload directory: ' . $e->getMessage());
            }

            // 1. Buat post dulu dengan cara yang lebih aman
            try {
                if (!\Illuminate\Support\Facades\Schema::hasTable('posts')) {
                    throw new \Exception('Table posts does not exist');
                }
                
                $post = new Post();
                $post->judul = $request->judul;
                $post->kategori_id = $request->kategori_id;
                
                // Tentukan petugas_id dengan benar
                if (auth('petugas')->check()) {
                    $post->petugas_id = auth('petugas')->user()->id;
                } else if (Auth::check()) {
                    $post->petugas_id = Auth::user()->id;
                } else {
                    Log::error('User not authenticated when creating post');
                    $post->petugas_id = 1;
                }
                
                $post->isi = $request->deskripsi ?? '';
                $post->status = 'published';
                $post->save();
                
                Log::info('Post created for gallery', ['post_id' => $post->id]);
            } catch (\Exception $e) {
                \Log::error('Error creating post: ' . $e->getMessage());
                if ($request->ajax()) {
                    return response()->json(['error' => 'Error creating post: ' . $e->getMessage()], 500);
                }
                return redirect()->back()->with('error', 'Error creating post: ' . $e->getMessage())->withInput();
            }

            // 2. Buat galeri yang terkait dengan post
            try {
                if (!\Illuminate\Support\Facades\Schema::hasTable('galery')) {
                    throw new \Exception('Table galery does not exist');
                }
                
                $galeri = new galery();
                $galeri->post_id = $post->id;
                $galeri->position = null;
                $galeri->status = 'aktif';
                $galeri->save();
                
                Log::info('Gallery created', ['gallery_id' => $galeri->id]);
            } catch (\Exception $e) {
                \Log::error('Error creating gallery: ' . $e->getMessage());
                // Rollback post
                try {
                    $post->delete();
                } catch (\Exception $deleteError) {
                    \Log::error('Error deleting post after gallery creation failed: ' . $deleteError->getMessage());
                }
                if ($request->ajax()) {
                    return response()->json(['error' => 'Error creating gallery: ' . $e->getMessage()], 500);
                }
                return redirect()->back()->with('error', 'Error creating gallery: ' . $e->getMessage())->withInput();
            }

            // 3. Upload dan simpan multiple files
            $uploadedFiles = [];
            if ($request->hasFile('fotos')) {
                try {
                    if (!\Illuminate\Support\Facades\Schema::hasTable('foto')) {
                        throw new \Exception('Table foto does not exist');
                    }
                    
                    foreach ($request->file('fotos') as $index => $foto) {
                        try {
                            $namaFoto = time() . '_' . $index . '.' . $foto->getClientOriginalExtension();
                            $foto->move(public_path('uploads/galeri'), $namaFoto);
                            
                            $fotoRecord = new Foto();
                            $fotoRecord->galery_id = $galeri->id;
                            $fotoRecord->file = $namaFoto;
                            $fotoRecord->save();
                            
                            $uploadedFiles[] = $namaFoto;
                        } catch (\Exception $e) {
                            \Log::error('Error uploading photo ' . $index . ': ' . $e->getMessage());
                            // Continue with other photos
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Error uploading photos: ' . $e->getMessage());
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'data' => $galeri,
                    'uploaded_files' => $uploadedFiles,
                    'message' => count($uploadedFiles) . ' foto berhasil diupload!'
                ]);
            }

            return redirect()->route('galeri.index')->with('success', count($uploadedFiles) . ' foto berhasil ditambahkan ke galeri!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('GaleriController store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            if ($request->ajax()) {
                return response()->json(['error' => 'Error creating gallery: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error creating gallery: ' . $e->getMessage())->withInput();
        }
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
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'kategori_id' => 'required|exists:kategori,id',
                'status' => 'required|in:aktif,nonaktif',
                'fotos' => 'nullable|array',
                'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240'
            ]);

            // Update galeri status
            try {
                $galeri->update([
                    'status' => $request->status
                ]);
            } catch (\Exception $e) {
                \Log::error('Error updating gallery status: ' . $e->getMessage());
                throw $e;
            }

            // Update post data
            try {
                if ($galeri->post) {
                    $galeri->post->update([
                        'judul' => $request->judul,
                        'isi' => $request->deskripsi,
                        'kategori_id' => $request->kategori_id,
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Error updating post: ' . $e->getMessage());
                throw $e;
            }

            // Handle new photo uploads
            if ($request->hasFile('fotos')) {
                try {
                    foreach ($request->file('fotos') as $foto) {
                        try {
                            $namaFoto = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                            $foto->move(public_path('uploads/galeri'), $namaFoto);
                            
                            $galeri->fotos()->create([
                                'file' => $namaFoto,
                                'galery_id' => $galeri->id
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('Error uploading photo: ' . $e->getMessage());
                            // Continue with other photos
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Error uploading photos: ' . $e->getMessage());
                }
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'data' => $galeri->load(['post.kategori', 'fotos'])]);
            }

            return redirect()->route('galeri.index')->with('success', 'Galeri berhasil diupdate!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('GaleriController update error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            if ($request->ajax()) {
                return response()->json(['error' => 'Error updating gallery: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error updating gallery: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(galery $galeri)
    {
        try {
            $galleryId = $galeri->id;
            $postId = $galeri->post_id;
            
            Log::info('Gallery deletion started', [
                'gallery_id' => $galleryId,
                'post_id' => $postId,
            ]);

            // Hapus foto files
            try {
                if ($galeri->fotos) {
                    foreach ($galeri->fotos as $foto) {
                        try {
                            $filePath = public_path('uploads/galeri/' . $foto->file);
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                            $foto->delete();
                        } catch (\Exception $e) {
                            \Log::error('Error deleting photo ' . $foto->id . ': ' . $e->getMessage());
                            // Continue with other photos
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error deleting photos: ' . $e->getMessage());
            }

            // Hapus post terkait
            try {
                if ($galeri->post) {
                    $galeri->post->delete();
                }
            } catch (\Exception $e) {
                \Log::error('Error deleting post: ' . $e->getMessage());
            }

            // Hapus galeri
            try {
                $galeri->delete();
            } catch (\Exception $e) {
                \Log::error('Error deleting gallery: ' . $e->getMessage());
                throw $e;
            }

            Log::info('Gallery deletion completed', ['gallery_id' => $galleryId]);

            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('galeri.index')->with('success', 'Galeri berhasil dihapus!');
        } catch (\Throwable $e) {
            \Log::error('GaleriController destroy error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            if (request()->ajax()) {
                return response()->json(['error' => 'Error deleting gallery: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error deleting gallery: ' . $e->getMessage());
        }
    }

    // Tambahan: toggle status (aktif/nonaktif atau verified/pending)
    public function toggleStatus(galery $galeri)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('galery')) {
                throw new \Exception('Table galery does not exist');
            }

            $galeri->status = $galeri->status === 'aktif' ? 'nonaktif' : 'aktif';
            $galeri->save();

            return response()->json(['success' => true, 'status' => $galeri->status]);
        } catch (\Throwable $e) {
            \Log::error('GaleriController toggleStatus error: ' . $e->getMessage());
            return response()->json(['error' => 'Error toggling status: ' . $e->getMessage()], 500);
        }
    }
}
