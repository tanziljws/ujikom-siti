<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foto;
use App\Models\Petugas;

class AdminProfileController extends Controller
{
    public function index()
    {
        try {
            $totalFotos = 0;
            $totalPetugas = 0;
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('foto')) {
                    $totalFotos = Foto::count();
                }
            } catch (\Exception $e) {
                \Log::error('Error counting foto: ' . $e->getMessage());
            }
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('petugas')) {
                    $totalPetugas = Petugas::count();
                }
            } catch (\Exception $e) {
                \Log::error('Error counting petugas: ' . $e->getMessage());
            }
            
            return view('admin.profile', compact('totalFotos', 'totalPetugas'));
        } catch (\Throwable $e) {
            \Log::error('AdminProfileController index error: ' . $e->getMessage());
            return view('admin.profile', ['totalFotos' => 0, 'totalPetugas' => 0]);
        }
    }

    public function update(Request $request)
    {
        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        // Update data user/admin
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Check current password
        if (!password_verify($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
        }

        // Update password
        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Password berhasil diperbarui.');
    }
}



