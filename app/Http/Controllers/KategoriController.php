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
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
            ]);

            if (!\Illuminate\Support\Facades\Schema::hasTable('kategori')) {
                throw new \Exception('Table kategori does not exist');
            }

            Kategori::create([
                'judul' => $request->judul,
            ]);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('KategoriController store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating kategori: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('kategori')) {
                return redirect()->route('kategori.index')->with('error', 'Table kategori does not exist');
            }
            
            $kategori = Kategori::findOrFail($id);
            return view('kategori.edit', compact('kategori'));
        } catch (\Throwable $e) {
            \Log::error('KategoriController edit error: ' . $e->getMessage());
            return redirect()->route('kategori.index')->with('error', 'Error loading kategori');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
            ]);

            if (!\Illuminate\Support\Facades\Schema::hasTable('kategori')) {
                throw new \Exception('Table kategori does not exist');
            }

            $kategori = Kategori::findOrFail($id);
            $kategori->update([
                'judul' => $request->judul,
            ]);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('KategoriController update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating kategori: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('kategori')) {
                throw new \Exception('Table kategori does not exist');
            }

            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
        } catch (\Throwable $e) {
            \Log::error('KategoriController destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting kategori: ' . $e->getMessage());
        }
    }
}
