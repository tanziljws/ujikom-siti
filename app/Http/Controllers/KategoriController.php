<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Foto;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        try {
            $kategori = collect([]);
            $totalFotos = 0;
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('kategori')) {
                    $kategori = Kategori::all();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading kategori: ' . $e->getMessage());
            }
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('foto')) {
                    $totalFotos = Foto::count();
                }
            } catch (\Exception $e) {
                \Log::error('Error counting foto: ' . $e->getMessage());
            }
            
            return view('kategori.index', compact('kategori', 'totalFotos'));
        } catch (\Throwable $e) {
            \Log::error('KategoriController index error: ' . $e->getMessage());
            return view('kategori.index', ['kategori' => collect([]), 'totalFotos' => 0]);
        }
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        Kategori::create([
            'judul' => $request->judul,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'judul' => $request->judul,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
