<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index()
    {
        try {
            $petugas = collect([]);
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('petugas')) {
                    $petugas = Petugas::all();
                }
            } catch (\Exception $e) {
                \Log::error('Error loading petugas: ' . $e->getMessage());
            }
            
            return view('petugas.index', compact('petugas'));
        } catch (\Throwable $e) {
            \Log::error('PetugasController index error: ' . $e->getMessage());
            return view('petugas.index', ['petugas' => collect([])]);
        }
    }

    public function create()
    {
        return view('petugas.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:petugas',
                'password' => 'required|string|min:6',
            ]);

            if (!\Illuminate\Support\Facades\Schema::hasTable('petugas')) {
                throw new \Exception('Table petugas does not exist');
            }

            Petugas::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('PetugasController store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating petugas: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Petugas $petuga)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('petugas')) {
                return redirect()->route('petugas.index')->with('error', 'Table petugas does not exist');
            }
            return view('petugas.edit', ['petugas' => $petuga]);
        } catch (\Throwable $e) {
            \Log::error('PetugasController edit error: ' . $e->getMessage());
            return redirect()->route('petugas.index')->with('error', 'Error loading petugas');
        }
    }

    public function update(Request $request, Petugas $petuga)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:petugas,email,' . $petuga->id,
                'password' => 'nullable|string|min:6',
            ]);

            if (!\Illuminate\Support\Facades\Schema::hasTable('petugas')) {
                throw new \Exception('Table petugas does not exist');
            }

            $data = [
                'username' => $request->username,
                'email' => $request->email,
            ];

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $petuga->update($data);

            return redirect()->route('petugas.index')->with('success', 'Petugas berhasil diupdate');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('PetugasController update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating petugas: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Petugas $petuga)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('petugas')) {
                throw new \Exception('Table petugas does not exist');
            }

            $petuga->delete();
            return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus');
        } catch (\Throwable $e) {
            \Log::error('PetugasController destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting petugas: ' . $e->getMessage());
        }
    }
}
