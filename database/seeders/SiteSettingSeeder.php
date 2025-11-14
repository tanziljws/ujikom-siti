<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Data default untuk halaman beranda
        $homeSettings = [
            [
                'key' => 'home_hero_title',
                'label' => 'Judul Utama Beranda',
                'type' => 'text',
                'value' => 'Selamat Datang di SMKN 4 BOGOR',
                'group' => 'home',
                'description' => 'Judul utama yang ditampilkan di bagian hero/header halaman beranda',
                'order' => 1
            ],
            [
                'key' => 'home_hero_subtitle',
                'label' => 'Subjudul Beranda',
                'type' => 'textarea',
                'value' => 'Mengembangkan potensi siswa melalui pendidikan berkualitas dan fasilitas modern.',
                'group' => 'home',
                'description' => 'Teks subjudul di bawah judul utama',
                'order' => 2
            ],
            [
                'key' => 'home_hero_image',
                'label' => 'Gambar Hero Beranda',
                'type' => 'image',
                'value' => '',
                'group' => 'home',
                'description' => 'Gambar latar belakang untuk bagian hero/header',
                'order' => 3
            ],
            [
                'key' => 'home_principal_title',
                'label' => 'Judul Sambutan Kepala Sekolah',
                'type' => 'text',
                'value' => 'Selamat Datang',
                'group' => 'home',
                'description' => 'Judul pada kartu sambutan kepala sekolah di beranda',
                'order' => 4
            ],
            [
                'key' => 'home_principal_quote',
                'label' => 'Teks Sambutan Kepala Sekolah',
                'type' => 'textarea',
                'value' => 'Kami percaya bahwa setiap siswa memiliki potensi luar biasa. Misi kami adalah mendampingi mereka untuk menemukan dan mengembangkan kemampuan terbaiknya. Mari jadikan setiap hari di sekolah sebagai langkah menuju masa depan yang sukses dan berkarakter.',
                'group' => 'home',
                'description' => 'Paragraf sambutan kepala sekolah di beranda',
                'order' => 5
            ],
            [
                'key' => 'home_principal_name',
                'label' => 'Nama Kepala Sekolah',
                'type' => 'text',
                'value' => 'Drs. Mulyamurpri Hartono, M.SI',
                'group' => 'home',
                'description' => 'Nama kepala sekolah yang ditampilkan di beranda',
                'order' => 6
            ],
            [
                'key' => 'home_principal_role',
                'label' => 'Jabatan Kepala Sekolah',
                'type' => 'text',
                'value' => 'Kepala Sekolah SMKN 4 Bogor',
                'group' => 'home',
                'description' => 'Jabatan kepala sekolah yang ditampilkan di beranda',
                'order' => 7
            ],
            [
                'key' => 'home_hero_button_text',
                'label' => 'Teks Tombol Hero',
                'type' => 'text',
                'value' => 'Lihat Selengkapnya',
                'group' => 'home',
                'description' => 'Teks pada tombol di bagian hero beranda',
                'order' => 8
            ]
        ];

        // Data default untuk halaman profil
        $profileSettings = [
            [
                'key' => 'profile_title',
                'label' => 'Judul Halaman Profil',
                'type' => 'text',
                'value' => 'Profil SMKN 4 BOGOR',
                'group' => 'profile',
                'description' => 'Judul halaman profil',
                'order' => 1
            ],
            [
                'key' => 'profile_content',
                'label' => 'Konten Profil',
                'type' => 'editor',
                'value' => '<p>SMK Negeri 4 Bogor adalah salah satu Sekolah Menengah Kejuruan Negeri yang terletak di Kota Bogor, Jawa Barat. Sekolah ini berdiri sejak tahun 1985 dan telah meluluskan ribuan siswa yang siap bekerja di dunia industri.</p><p>Dengan fasilitas yang lengkap dan tenaga pengajar yang profesional, SMKN 4 Bogor siap mencetak lulusan yang kompeten di bidangnya masing-masing.</p>',
                'group' => 'profile',
                'description' => 'Konten lengkap halaman profil',
                'order' => 2
            ],
            [
                'key' => 'profile_image',
                'label' => 'Gambar Profil',
                'type' => 'image',
                'value' => '',
                'group' => 'profile',
                'description' => 'Gambar untuk halaman profil',
                'order' => 3
            ]
        ];

        // Data default untuk visi misi
        $visionMissionSettings = [
            [
                'key' => 'vision_title',
                'label' => 'Judul Visi',
                'type' => 'text',
                'value' => 'Visi',
                'group' => 'vision_mission',
                'description' => 'Judul bagian visi',
                'order' => 1
            ],
            [
                'key' => 'vision_content',
                'label' => 'Isi Visi',
                'type' => 'textarea',
                'value' => 'Menjadi lembaga pendidikan kejuruan yang unggul, berkarakter, dan berdaya saing global pada tahun 2025.',
                'group' => 'vision_mission',
                'description' => 'Teks visi sekolah',
                'order' => 2
            ],
            [
                'key' => 'mission_title',
                'label' => 'Judul Misi',
                'type' => 'text',
                'value' => 'Misi',
                'group' => 'vision_mission',
                'description' => 'Judul bagian misi',
                'order' => 3
            ],
            [
                'key' => 'mission_content',
                'label' => 'Isi Misi',
                'type' => 'editor',
                'value' => '<ol><li>Menyelenggarakan pendidikan dan pelatihan yang berkualitas sesuai dengan standar nasional dan internasional</li><li>Mengembangkan kurikulum berbasis kompetensi yang sesuai dengan kebutuhan dunia kerja</li><li>Meningkatkan kualitas tenaga pendidik dan kependidikan secara berkelanjutan</li><li>Menyediakan sarana dan prasarana pendidikan yang memadai dan relevan</li><li>Menjalin kerjasama dengan dunia usaha dan industri dalam rangka peningkatan mutu lulusan</li></ol>',
                'group' => 'vision_mission',
                'description' => 'Teks misi sekolah dalam format list',
                'order' => 4
            ]
        ];

        // Data default untuk kontak
        $contactSettings = [
            [
                'key' => 'contact_title',
                'label' => 'Judul Halaman Kontak',
                'type' => 'text',
                'value' => 'Hubungi Kami',
                'group' => 'contact',
                'description' => 'Judul halaman kontak',
                'order' => 1
            ],
            [
                'key' => 'contact_address',
                'label' => 'Alamat',
                'type' => 'textarea',
                'value' => 'Jl. Raya Tajur No. 84, Kota Bogor, Jawa Barat 16134',
                'group' => 'contact',
                'description' => 'Alamat lengkap sekolah',
                'order' => 2
            ],
            [
                'key' => 'contact_phone',
                'label' => 'Telepon',
                'type' => 'text',
                'value' => '(0251) 1234567',
                'group' => 'contact',
                'description' => 'Nomor telepon sekolah',
                'order' => 3
            ],
            [
                'key' => 'contact_email',
                'label' => 'Email',
                'type' => 'text',
                'value' => 'info@smkn4bogor.sch.id',
                'group' => 'contact',
                'description' => 'Alamat email resmi sekolah',
                'order' => 4
            ],
            [
                'key' => 'contact_website',
                'label' => 'Website',
                'type' => 'text',
                'value' => 'www.smkn4bogor.sch.id',
                'group' => 'contact',
                'description' => 'Alamat website resmi sekolah',
                'order' => 5
            ],
            [
                'key' => 'contact_map_embed',
                'label' => 'Embed Map',
                'type' => 'textarea',
                'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.358216505442!2d106.79727831532822!3d-6.597005365229759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5d9c8b5b5b5%3A0x5b4b5b5b5b5b5b5b!2sSMKN%204%20Bogor!5e0!3m2!1sen!2sid!4v1678886405123!5m2!1sen!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'group' => 'contact',
                'description' => 'Kode embed Google Maps atau URL Google Maps. Untuk mendapatkan embed code: 1) Buka Google Maps 2) Cari lokasi 3) Klik "Share" 4) Pilih tab "Embed a map" 5) Salin kode iframe',
                'order' => 6
            ]
        ];

        // Data default untuk informasi
        $infoSettings = [
            [
                'key' => 'info_title',
                'label' => 'Judul Halaman Informasi',
                'type' => 'text',
                'value' => 'Informasi Terbaru',
                'group' => 'information',
                'description' => 'Judul halaman informasi',
                'order' => 1
            ],
            [
                'key' => 'info_description',
                'label' => 'Deskripsi Halaman Informasi',
                'type' => 'textarea',
                'value' => 'Dapatkan informasi terbaru seputar kegiatan, pengumuman, dan berita dari SMKN 4 Bogor.',
                'group' => 'information',
                'description' => 'Deskripsi singkat halaman informasi',
                'order' => 2
            ],
            // Program Keahlian
            [
                'key' => 'expertise_title',
                'label' => 'Judul Program Keahlian',
                'type' => 'text',
                'value' => 'Program Keahlian',
                'group' => 'information',
                'description' => 'Judul bagian program keahlian',
                'order' => 3
            ],
            [
                'key' => 'expertise_1_name',
                'label' => 'Keahlian 1 - Nama',
                'type' => 'text',
                'value' => 'Rekayasa Perangkat Lunak',
                'group' => 'information',
                'description' => 'Nama program keahlian pertama',
                'order' => 4
            ],
            [
                'key' => 'expertise_1_description',
                'label' => 'Keahlian 1 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Mempelajari pengembangan perangkat lunak, pemrograman, dan teknologi informasi terkini.',
                'group' => 'information',
                'description' => 'Deskripsi program keahlian pertama',
                'order' => 5
            ],
            [
                'key' => 'expertise_2_name',
                'label' => 'Keahlian 2 - Nama',
                'type' => 'text',
                'value' => 'Teknik Komputer dan Jaringan',
                'group' => 'information',
                'description' => 'Nama program keahlian kedua',
                'order' => 6
            ],
            [
                'key' => 'expertise_2_description',
                'label' => 'Keahlian 2 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Fokus pada perakitan komputer, jaringan komputer, dan keamanan sistem.',
                'group' => 'information',
                'description' => 'Deskripsi program keahlian kedua',
                'order' => 7
            ],
            [
                'key' => 'expertise_3_name',
                'label' => 'Keahlian 3 - Nama',
                'type' => 'text',
                'value' => 'Multimedia',
                'group' => 'information',
                'description' => 'Nama program keahlian ketiga',
                'order' => 8
            ],
            [
                'key' => 'expertise_3_description',
                'label' => 'Keahlian 3 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Mengembangkan keterampilan dalam desain grafis, animasi, dan produksi media.',
                'group' => 'information',
                'description' => 'Deskripsi program keahlian ketiga',
                'order' => 9
            ],
            [
                'key' => 'expertise_4_name',
                'label' => 'Keahlian 4 - Nama',
                'type' => 'text',
                'value' => 'Otomatisasi dan Tata Kelola Perkantoran',
                'group' => 'information',
                'description' => 'Nama program keahlian keempat',
                'order' => 10
            ],
            [
                'key' => 'expertise_4_description',
                'label' => 'Keahlian 4 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Menguasai keterampilan administrasi perkantoran dan teknologi informasi.',
                'group' => 'information',
                'description' => 'Deskripsi program keahlian keempat',
                'order' => 11
            ]
        ];

        // Data default untuk agenda
        $agendaSettings = [
            [
                'key' => 'agenda_title',
                'label' => 'Judul Halaman Agenda',
                'type' => 'text',
                'value' => 'Agenda Kegiatan',
                'group' => 'agenda',
                'description' => 'Judul halaman agenda',
                'order' => 1
            ],
            [
                'key' => 'agenda_description',
                'label' => 'Deskripsi Halaman Agenda',
                'type' => 'textarea',
                'value' => 'Jadwal dan agenda kegiatan terbaru SMKN 4 Bogor. Pantau terus jadwal kegiatan sekolah kami.',
                'group' => 'agenda',
                'description' => 'Deskripsi singkat halaman agenda',
                'order' => 2
            ]
        ];

        // Data default untuk fasilitas sekolah
        $facilitySettings = [
            [
                'key' => 'facilities_title',
                'label' => 'Judul Fasilitas Sekolah',
                'type' => 'text',
                'value' => 'Fasilitas Sekolah',
                'group' => 'information',
                'description' => 'Judul bagian fasilitas sekolah',
                'order' => 12
            ],
            [
                'key' => 'facility_1_title',
                'label' => 'Fasilitas 1 - Judul',
                'type' => 'text',
                'value' => 'Laboratorium Komputer',
                'group' => 'information',
                'description' => 'Judul fasilitas pertama',
                'order' => 13
            ],
            [
                'key' => 'facility_1_icon',
                'label' => 'Fasilitas 1 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-laptop',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk fasilitas pertama',
                'order' => 14
            ],
            [
                'key' => 'facility_1_description',
                'label' => 'Fasilitas 1 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Ruang laboratorium modern dengan komputer terbaru untuk mendukung pembelajaran teknologi informasi.',
                'group' => 'information',
                'description' => 'Deskripsi fasilitas pertama',
                'order' => 15
            ],
            [
                'key' => 'facility_2_title',
                'label' => 'Fasilitas 2 - Judul',
                'type' => 'text',
                'value' => 'Laboratorium IPA',
                'group' => 'information',
                'description' => 'Judul fasilitas kedua',
                'order' => 16
            ],
            [
                'key' => 'facility_2_icon',
                'label' => 'Fasilitas 2 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-flask',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk fasilitas kedua',
                'order' => 17
            ],
            [
                'key' => 'facility_2_description',
                'label' => 'Fasilitas 2 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Fasilitas laboratorium sains lengkap dengan peralatan modern untuk eksperimen fisika, kimia, dan biologi.',
                'group' => 'information',
                'description' => 'Deskripsi fasilitas kedua',
                'order' => 18
            ],
            [
                'key' => 'facility_3_title',
                'label' => 'Fasilitas 3 - Judul',
                'type' => 'text',
                'value' => 'Perpustakaan Digital',
                'group' => 'information',
                'description' => 'Judul fasilitas ketiga',
                'order' => 19
            ],
            [
                'key' => 'facility_3_icon',
                'label' => 'Fasilitas 3 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-book',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk fasilitas ketiga',
                'order' => 20
            ],
            [
                'key' => 'facility_3_description',
                'label' => 'Fasilitas 3 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Perpustakaan dengan koleksi buku dan akses digital untuk mendukung kegiatan belajar mengajar.',
                'group' => 'information',
                'description' => 'Deskripsi fasilitas ketiga',
                'order' => 21
            ],
            [
                'key' => 'facility_4_title',
                'label' => 'Fasilitas 4 - Judul',
                'type' => 'text',
                'value' => 'Fasilitas Olahraga',
                'group' => 'information',
                'description' => 'Judul fasilitas keempat',
                'order' => 22
            ],
            [
                'key' => 'facility_4_icon',
                'label' => 'Fasilitas 4 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-dumbbell',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk fasilitas keempat',
                'order' => 23
            ],
            [
                'key' => 'facility_4_description',
                'label' => 'Fasilitas 4 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Lapangan olahraga dan fasilitas pendukung untuk kegiatan ekstrakurikuler dan kompetisi.',
                'group' => 'information',
                'description' => 'Deskripsi fasilitas keempat',
                'order' => 24
            ],
            [
                'key' => 'facility_5_title',
                'label' => 'Fasilitas 5 - Judul',
                'type' => 'text',
                'value' => 'Kantin Sekolah',
                'group' => 'information',
                'description' => 'Judul fasilitas kelima',
                'order' => 25
            ],
            [
                'key' => 'facility_5_icon',
                'label' => 'Fasilitas 5 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-utensils',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk fasilitas kelima',
                'order' => 26
            ],
            [
                'key' => 'facility_5_description',
                'label' => 'Fasilitas 5 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Kantin yang menyediakan makanan sehat dan bergizi untuk siswa dan staf pengajar.',
                'group' => 'information',
                'description' => 'Deskripsi fasilitas kelima',
                'order' => 27
            ],
            [
                'key' => 'facility_6_title',
                'label' => 'Fasilitas 6 - Judul',
                'type' => 'text',
                'value' => 'Wi-Fi Sekolah',
                'group' => 'information',
                'description' => 'Judul fasilitas keenam',
                'order' => 28
            ],
            [
                'key' => 'facility_6_icon',
                'label' => 'Fasilitas 6 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-wifi',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk fasilitas keenam',
                'order' => 29
            ],
            [
                'key' => 'facility_6_description',
                'label' => 'Fasilitas 6 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Akses internet cepat di seluruh area sekolah untuk mendukung pembelajaran digital.',
                'group' => 'information',
                'description' => 'Deskripsi fasilitas keenam',
                'order' => 30
            ]
        ];

        // Data default untuk prestasi sekolah
        $achievementSettings = [
            [
                'key' => 'achievements_title',
                'label' => 'Judul Prestasi Sekolah',
                'type' => 'text',
                'value' => 'Prestasi Sekolah',
                'group' => 'information',
                'description' => 'Judul bagian prestasi sekolah',
                'order' => 31
            ],
            [
                'key' => 'achievement_1_title',
                'label' => 'Prestasi 1 - Judul',
                'type' => 'text',
                'value' => 'Juara 1 LKS Provinsi 2023',
                'group' => 'information',
                'description' => 'Judul prestasi pertama',
                'order' => 32
            ],
            [
                'key' => 'achievement_1_icon',
                'label' => 'Prestasi 1 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-trophy',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk prestasi pertama',
                'order' => 33
            ],
            [
                'key' => 'achievement_1_description',
                'label' => 'Prestasi 1 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Tim siswa meraih juara pertama dalam Lomba Kompetensi Siswa tingkat provinsi untuk bidang Teknologi Informasi.',
                'group' => 'information',
                'description' => 'Deskripsi prestasi pertama',
                'order' => 34
            ],
            [
                'key' => 'achievement_1_date',
                'label' => 'Prestasi 1 - Tanggal',
                'type' => 'text',
                'value' => 'Oktober 2023',
                'group' => 'information',
                'description' => 'Tanggal prestasi pertama',
                'order' => 35
            ],
            [
                'key' => 'achievement_2_title',
                'label' => 'Prestasi 2 - Judul',
                'type' => 'text',
                'value' => 'Juara 2 Olimpiade Matematika',
                'group' => 'information',
                'description' => 'Judul prestasi kedua',
                'order' => 36
            ],
            [
                'key' => 'achievement_2_icon',
                'label' => 'Prestasi 2 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-medal',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk prestasi kedua',
                'order' => 37
            ],
            [
                'key' => 'achievement_2_description',
                'label' => 'Prestasi 2 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Siswa kami berhasil meraih medali perak dalam Olimpiade Matematika tingkat nasional.',
                'group' => 'information',
                'description' => 'Deskripsi prestasi kedua',
                'order' => 38
            ],
            [
                'key' => 'achievement_2_date',
                'label' => 'Prestasi 2 - Tanggal',
                'type' => 'text',
                'value' => 'September 2023',
                'group' => 'information',
                'description' => 'Tanggal prestasi kedua',
                'order' => 39
            ],
            [
                'key' => 'achievement_3_title',
                'label' => 'Prestasi 3 - Judul',
                'type' => 'text',
                'value' => 'Sekolah Adiwiyata Mandiri',
                'group' => 'information',
                'description' => 'Judul prestasi ketiga',
                'order' => 40
            ],
            [
                'key' => 'achievement_3_icon',
                'label' => 'Prestasi 3 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-award',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk prestasi ketiga',
                'order' => 41
            ],
            [
                'key' => 'achievement_3_description',
                'label' => 'Prestasi 3 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Penghargaan dari Kementerian Lingkungan Hidup sebagai sekolah peduli dan berbudaya lingkungan.',
                'group' => 'information',
                'description' => 'Deskripsi prestasi ketiga',
                'order' => 42
            ],
            [
                'key' => 'achievement_3_date',
                'label' => 'Prestasi 3 - Tanggal',
                'type' => 'text',
                'value' => 'Agustus 2023',
                'group' => 'information',
                'description' => 'Tanggal prestasi ketiga',
                'order' => 43
            ],
            [
                'key' => 'achievement_4_title',
                'label' => 'Prestasi 4 - Judul',
                'type' => 'text',
                'value' => 'Sertifikasi Internasional',
                'group' => 'information',
                'description' => 'Judul prestasi keempat',
                'order' => 44
            ],
            [
                'key' => 'achievement_4_icon',
                'label' => 'Prestasi 4 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-certificate',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk prestasi keempat',
                'order' => 45
            ],
            [
                'key' => 'achievement_4_description',
                'label' => 'Prestasi 4 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Lebih dari 200 siswa lulus dengan sertifikasi internasional dalam bidang teknologi informasi.',
                'group' => 'information',
                'description' => 'Deskripsi prestasi keempat',
                'order' => 46
            ],
            [
                'key' => 'achievement_4_date',
                'label' => 'Prestasi 4 - Tanggal',
                'type' => 'text',
                'value' => 'Juli 2023',
                'group' => 'information',
                'description' => 'Tanggal prestasi keempat',
                'order' => 47
            ],
            [
                'key' => 'achievement_5_title',
                'label' => 'Prestasi 5 - Judul',
                'type' => 'text',
                'value' => 'Juara 1 Turnamen Catur',
                'group' => 'information',
                'description' => 'Judul prestasi kelima',
                'order' => 48
            ],
            [
                'key' => 'achievement_5_icon',
                'label' => 'Prestasi 5 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-chess',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk prestasi kelima',
                'order' => 49
            ],
            [
                'key' => 'achievement_5_description',
                'label' => 'Prestasi 5 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Tim catur sekolah meraih juara pertama dalam turnamen antar sekolah se-Kota Bogor.',
                'group' => 'information',
                'description' => 'Deskripsi prestasi kelima',
                'order' => 50
            ],
            [
                'key' => 'achievement_5_date',
                'label' => 'Prestasi 5 - Tanggal',
                'type' => 'text',
                'value' => 'Juni 2023',
                'group' => 'information',
                'description' => 'Tanggal prestasi kelima',
                'order' => 51
            ],
            [
                'key' => 'achievement_6_title',
                'label' => 'Prestasi 6 - Judul',
                'type' => 'text',
                'value' => 'Festival Musik Pelajar',
                'group' => 'information',
                'description' => 'Judul prestasi keenam',
                'order' => 52
            ],
            [
                'key' => 'achievement_6_icon',
                'label' => 'Prestasi 6 - Ikon',
                'type' => 'text',
                'value' => 'fas fa-music',
                'group' => 'information',
                'description' => 'Ikon Font Awesome untuk prestasi keenam',
                'order' => 53
            ],
            [
                'key' => 'achievement_6_description',
                'label' => 'Prestasi 6 - Deskripsi',
                'type' => 'textarea',
                'value' => 'Grup band sekolah meraih penghargaan khusus dalam Festival Musik Pelajar tingkat provinsi.',
                'group' => 'information',
                'description' => 'Deskripsi prestasi keenam',
                'order' => 54
            ],
            [
                'key' => 'achievement_6_date',
                'label' => 'Prestasi 6 - Tanggal',
                'type' => 'text',
                'value' => 'Mei 2023',
                'group' => 'information',
                'description' => 'Tanggal prestasi keenam',
                'order' => 55
            ]
        ];

        // Data default untuk footer
        $footerSettings = [
            [
                'key' => 'footer_about',
                'label' => 'Tentang Kami',
                'type' => 'editor',
                'value' => '<p>SMKN 4 Bogor adalah lembaga pendidikan kejuruan yang berkomitmen untuk menghasilkan lulusan yang kompeten dan berkarakter.</p>',
                'group' => 'footer',
                'description' => 'Teks tentang sekolah di footer',
                'order' => 1
            ],
            [
                'key' => 'footer_copyright',
                'label' => 'Teks Hak Cipta',
                'type' => 'text',
                'value' => '© ' . date('Y') . ' SMKN 4 Bogor. All Rights Reserved.',
                'group' => 'footer',
                'description' => 'Teks hak cipta di footer',
                'order' => 2
            ],
            [
                'key' => 'social_facebook',
                'label' => 'Facebook',
                'type' => 'text',
                'value' => 'https://facebook.com/smkn4bogor',
                'group' => 'footer',
                'description' => 'Link ke akun Facebook',
                'order' => 3
            ],
            [
                'key' => 'social_twitter',
                'label' => 'Twitter',
                'type' => 'text',
                'value' => 'https://twitter.com/smkn4bogor',
                'group' => 'footer',
                'description' => 'Link ke akun Twitter',
                'order' => 4
            ],
            [
                'key' => 'social_instagram',
                'label' => 'Instagram',
                'type' => 'text',
                'value' => 'https://instagram.com/smkn4bogor',
                'group' => 'footer',
                'description' => 'Link ke akun Instagram',
                'order' => 5
            ],
            [
                'key' => 'social_youtube',
                'label' => 'YouTube',
                'type' => 'text',
                'value' => 'https://youtube.com/c/smkn4bogor',
                'group' => 'footer',
                'description' => 'Link ke channel YouTube',
                'order' => 6
            ]
        ];

        // Gabungkan semua pengaturan
        $allSettings = array_merge(
            $homeSettings,
            $profileSettings,
            $visionMissionSettings,
            $contactSettings,
            $infoSettings,
            $agendaSettings,
            $facilitySettings,
            $achievementSettings,
            $footerSettings
        );

        // Masukkan data ke dalam database
        foreach ($allSettings as $setting) {
            // Gunakan updateOrCreate untuk memastikan data selalu ada
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}