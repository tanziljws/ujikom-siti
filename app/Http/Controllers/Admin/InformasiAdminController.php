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
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'icon' => 'nullable|string|max:255',
                'date' => 'required|date',
                'status' => 'required|in:aktif,nonaktif',
                'order' => 'nullable|integer|min:0',
            ]);

            if (!\Illuminate\Support\Facades\Schema::hasTable('informasi')) {
                throw new \Exception('Table informasi does not exist');
            }

            Informasi::create($validated);

            return redirect()->route('admin.informasi-items.index')
                ->with('success', 'Informasi berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('InformasiAdminController store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating informasi: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Informasi $informasi_item)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('informasi')) {
                return redirect()->route('admin.informasi-items.index')->with('error', 'Table informasi does not exist');
            }
            return view('admin.informasi-items.edit', ['informasi' => $informasi_item]);
        } catch (\Throwable $e) {
            \Log::error('InformasiAdminController edit error: ' . $e->getMessage());
            return redirect()->route('admin.informasi-items.index')->with('error', 'Error loading informasi');
        }
    }

    public function update(Request $request, Informasi $informasi_item)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'icon' => 'nullable|string|max:255',
                'date' => 'required|date',
                'status' => 'required|in:aktif,nonaktif',
                'order' => 'nullable|integer|min:0',
            ]);

            if (!\Illuminate\Support\Facades\Schema::hasTable('informasi')) {
                throw new \Exception('Table informasi does not exist');
            }

            $informasi_item->update($validated);

            return redirect()->route('admin.informasi-items.index')
                ->with('success', 'Informasi berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('InformasiAdminController update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating informasi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Informasi $informasi_item)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('informasi')) {
                throw new \Exception('Table informasi does not exist');
            }

            $informasi_item->delete();

            return redirect()->route('admin.informasi-items.index')
                ->with('success', 'Informasi berhasil dihapus.');
        } catch (\Throwable $e) {
            \Log::error('InformasiAdminController destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting informasi: ' . $e->getMessage());
        }
    }
}
