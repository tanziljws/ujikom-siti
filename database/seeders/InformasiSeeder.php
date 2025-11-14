<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Informasi;
use Carbon\Carbon;

class InformasiSeeder extends Seeder
{
    public function run(): void
    {
        // Kalau tidak mau hapus data lama, hapus baris berikut
        Informasi::truncate();

        $items = [
            [
                'title'       => 'Pengumuman PPDB 2025',
                'description' => 'Pendaftaran Peserta Didik Baru (PPDB) SMKN 4 Bogor tahun pelajaran 2025/2026 telah dibuka. Silakan mengakses halaman resmi sekolah untuk persyaratan dan jadwal lengkap.',
                'icon'        => 'fas fa-bullhorn',
                'date'        => Carbon::parse('2025-03-01'),
                'status'      => 'aktif',
                'order'       => 1,
            ],
            [
                'title'       => 'Libur Akhir Semester Genap',
                'description' => 'Libur akhir semester genap dimulai pada tanggal 20 Juni 2025 sampai dengan 5 Juli 2025. Kegiatan belajar mengajar akan dimulai kembali pada 7 Juli 2025.',
                'icon'        => 'fas fa-calendar-alt',
                'date'        => Carbon::parse('2025-06-15'),
                'status'      => 'aktif',
                'order'       => 2,
            ],
            [
                'title'       => 'Lomba Kompetensi Siswa (LKS) Tingkat Kota',
                'description' => 'SMKN 4 Bogor akan mengikuti Lomba Kompetensi Siswa (LKS) tingkat Kota Bogor. Mohon dukungan dan doa dari seluruh warga sekolah.',
                'icon'        => 'fas fa-trophy',
                'date'        => Carbon::parse('2025-04-10'),
                'status'      => 'aktif',
                'order'       => 3,
            ],
            [
                'title'       => 'Rapat Orang Tua/Wali Siswa',
                'description' => 'Rapat orang tua/wali siswa kelas X, XI, dan XII akan dilaksanakan pada hari Sabtu, 12 April 2025, pukul 08.00 WIB di aula sekolah.',
                'icon'        => 'fas fa-users',
                'date'        => Carbon::parse('2025-04-01'),
                'status'      => 'aktif',
                'order'       => 4,
            ],
            [
                'title'       => 'Pembagian Rapor Semester Genap',
                'description' => 'Pembagian rapor semester genap tahun pelajaran 2024/2025 akan dilaksanakan pada hari Jumat, 19 Juni 2025.',
                'icon'        => 'fas fa-file-alt',
                'date'        => Carbon::parse('2025-06-18'),
                'status'      => 'aktif',
                'order'       => 5,
            ],
        ];

        foreach ($items as $item) {
            Informasi::create($item);
        }
    }
}