<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void 
    {
        // Hapus data lama hanya jika belum ada data sama sekali
        // Cek apakah sudah ada petugas
        $existingPetugas = Petugas::count();
        $existingAdminUser = User::whereIn('email', ['admin@galeri-edu.com', 'admin@gmail.com'])->count();
        $existingStaffUser = User::whereIn('email', ['siti@galeri-edu.com', 'siti@gmail.com'])->count();
        
        // Jika belum ada data sama sekali, maka buat data default
        if ($existingPetugas == 0 && $existingAdminUser == 0 && $existingStaffUser == 0) {
            // Buat data petugas (admin)
            Petugas::create([
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
            ]);

            // Buat data petugas (staff)
            Petugas::create([
                'username' => 'staff',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('staff123'),
            ]);

            // Buat user untuk login admin
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@galeri-edu.com',
                'password' => Hash::make('admin123'),
            ]);

            // Buat user untuk login staff
            User::create([
                'name' => 'Siti Nuraeni',
                'email' => 'siti@galeri-edu.com',
                'password' => Hash::make('siti123'),
            ]);

            User::create([
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@gmail.com',
                'password' => Hash::make('kepsek123'),
            ]);
        }
        // Jika sudah ada data, tidak perlu menghapus atau membuat ulang
    }
}