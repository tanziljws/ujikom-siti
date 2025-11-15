<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiAdminController extends Controller
{
    public function index()
    {
        try {
            $informasiItems = collect([]);
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('informasi')) {
                    $informasiItems = Informasi::orderBy('order')->orderByDesc('date')->paginate(10);
                }
            } catch (\Exception $e) {
                \Log::error('Error loading informasi items: ' . $e->getMessage());
            }
            
            return view('admin.informasi-items.index', compact('informasiItems'));
        } catch (\Throwable $e) {
            \Log::error('InformasiAdminController index error: ' . $e->getMessage());
            return view('admin.informasi-items.index', ['informasiItems' => collect([])]);
        }
    }

    public function create()
    {
        return view('admin.informasi-items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:aktif,nonaktif',
            'order' => 'nullable|integer|min:0',
        ]);

        Informasi::create($validated);

        return redirect()->route('admin.informasi-items.index')
            ->with('success', 'Informasi berhasil ditambahkan.');
    }

    public function edit(Informasi $informasi_item)
    {
        return view('admin.informasi-items.edit', ['informasi' => $informasi_item]);
    }

    public function update(Request $request, Informasi $informasi_item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:aktif,nonaktif',
            'order' => 'nullable|integer|min:0',
        ]);

        $informasi_item->update($validated);

        return redirect()->route('admin.informasi-items.index')
            ->with('success', 'Informasi berhasil diperbarui.');
    }

    public function destroy(Informasi $informasi_item)
    {
        $informasi_item->delete();

        return redirect()->route('admin.informasi-items.index')
            ->with('success', 'Informasi berhasil dihapus.');
    }
}
