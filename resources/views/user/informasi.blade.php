<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi - SMKN 4 BOGOR</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
            scroll-behavior: smooth;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 40px;
        }
        
        @media (min-width: 1400px) {
            .container {
                padding: 0 60px;
            }
        }
        
        /* Header Section - Modern Navbar Style */
        .header {
            background: white;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }
        
        .branding {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .brand-icon {
            width: 60px;
            height: 60px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .brand-icon svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 2px 4px rgba(30, 58, 138, 0.2));
        }
        
        .brand-text h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            line-height: 1.2;
        }
        
        .brand-text p {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            margin: 0;
            line-height: 1;
        }
        
        /* Navigation Menu */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-links li a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .nav-links li a:hover {
            color: #3b82f6;
            background: #f1f5f9;
        }
        
        .nav-links li a.active {
            color: white;
            background: #3b82f6;
        }
        
        .login-btn {
            background: #3b82f6;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .login-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        /* Main Content */
        .main-content {
            padding: 80px 0;
            background: #f8f9fa;
            min-height: calc(100vh - 100px);
        }
        
        .main-content .container {
            max-width: 100%;
            padding: 0 40px;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            color: #64748b;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 40px;
            margin-bottom: 60px;
            padding: 0;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .info-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
        }
        
        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px -5px rgba(0, 0, 0, 0.15);
        }
        
        .info-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        
        .info-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        
        .info-description {
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            font-size: 1.05rem;
        }
        
        .info-date {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #3b82f6;
            font-weight: 500;
            font-size: 1rem;
        }
        
        .info-date i {
            font-size: 1.1rem;
        }
        
        .achievement-section {
            background: white;
            border-radius: 20px;
            padding: 3rem 2.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            margin-bottom: 40px;
        }
        
        .achievement-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2.5rem;
            text-align: center;
        }
        
        .achievement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
        }
        
        .achievement-item {
            text-align: center;
            padding: 2rem 1.5rem;
            background: #f8f9fa;
            border-radius: 16px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .achievement-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
            border-color: #3b82f6;
        }
        
        .achievement-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 1.25rem;
        }
        
        .achievement-text {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
            line-height: 1.5;
        }
        
        /* News list + Achievement list (compact) */
        .news-section { background: white; border: 1px solid #e5e7eb; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 40px; }
        .news-header { display:flex; align-items:center; gap:12px; padding:20px 28px; background: #0f172a; color:#fff; font-weight:700; font-size: 1.25rem; border-radius:20px 20px 0 0; }
        .news-list { padding: 0; margin: 0; list-style: none; }
        .news-item { display:flex; gap:16px; align-items:flex-start; padding:20px 28px; border-top: 1px solid #eef2f7; }
        .news-item:hover { background: #f8fafc; }
        .news-item .news-icon { color:#3b82f6; font-size: 1.25rem; }
        .news-item .news-title { font-weight:700; color:#1f2937; margin-bottom:8px; font-size: 1.1rem; }
        .news-item .news-meta { display:flex; gap:10px; align-items:center; color:#64748b; font-size: 0.95rem; margin-bottom:8px; }
        .news-item .news-text { color:#64748b; font-size: 1rem; line-height: 1.6; }
        
        .awards-section { background: white; border: 1px solid #e5e7eb; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 40px; }
        .awards-header { display:flex; align-items:center; gap:12px; padding:20px 28px; background: #0f172a; color:#fff; font-weight:700; font-size: 1.25rem; border-radius:20px 20px 0 0; }
        .awards-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(380px,1fr)); gap:24px; padding:28px; }
        .award-card { background:#f8fafc; border:1px solid #e5e7eb; border-radius:16px; padding:20px 24px; display:flex; gap:16px; align-items:center; transition: all 0.3s ease; }
        .award-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .award-icon { width:56px; height:56px; border-radius:50%; background: linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink: 0; }
        .award-title { font-weight:700; color:#1f2937; font-size: 1.1rem; margin-bottom: 4px; }
        .award-sub { color:#64748b; font-size: 0.95rem; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                min-height: auto;
                padding: 2rem 0;
            }
            
            .page-header {
                padding-top: 2rem;
                margin-bottom: 2rem;
            }
            
            .page-title {
                font-size: 1.5rem !important;
            }
            
            .page-subtitle {
                font-size: 1rem !important;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 0;
            }
            
            .info-card {
                padding: 1.5rem;
            }
            
            .info-icon {
                width: 60px;
                height: 60px;
                font-size: 1.75rem;
            }
            
            .info-title {
                font-size: 1.25rem;
            }
            
            .info-description {
                font-size: 0.95rem;
            }
            
            .brand-text h1 {
                font-size: 22px;
            }
            
            .brand-text p {
                font-size: 14px;
            }
            
            .brand-icon {
                width: 60px;
                height: 70px;
            }
            
            .awards-grid {
                grid-template-columns: 1fr;
                padding: 20px;
            }
            
            .achievement-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <nav class="navbar">
                <div class="branding">
                    <div class="brand-icon">
                        <img src="{{ asset('images/LOGO_SMKN_4.png') }}" alt="SMKN 4 Bogor Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div class="brand-text">
                        <h1>SMKN 4</h1>
                        <p>Bogor</p>
                    </div>
                </div>
                
                <div class="nav-menu">
                    <ul class="nav-links">
                        <li><a href="{{ route('user.dashboard') }}" class="nav-link">Beranda</a></li>
                        <li><a href="{{ route('user.agenda') }}" class="nav-link">Agenda</a></li>
                        <li><a href="{{ route('user.informasi') }}" class="nav-link active">Informasi</a></li>
                        <li><a href="{{ route('user.gallery') }}" class="nav-link">Galeri</a></li>
                    </ul>
                </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Informasi Terkini</h1>
                <p class="page-subtitle">Berita dan pengumuman terbaru dari SMKN 4 Bogor</p>
            </div>
            
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="info-title">Prestasi Juara 1 Lomba Kompetensi</h3>
                    <p class="info-description">Siswa SMKN 4 Bogor berhasil meraih juara 1 dalam Lomba Kompetensi Siswa tingkat provinsi Jawa Barat di bidang Teknologi Informasi.</p>
                    <div class="info-date">
                        <i class="fas fa-calendar"></i>
                        <span>15 Januari 2024</span>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="info-title">Penerimaan Siswa Baru 2024</h3>
                    <p class="info-description">Pendaftaran siswa baru untuk tahun ajaran 2024/2025 telah dibuka. Daftarkan diri Anda sekarang dan bergabunglah dengan keluarga besar SMKN 4 Bogor.</p>
                    <div class="info-date">
                        <i class="fas fa-calendar"></i>
                        <span>10 Januari 2024</span>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3 class="info-title">Update Sistem Galeri Terbaru</h3>
                    <p class="info-description">Website galeri sekolah telah diperbarui dengan fitur-fitur terbaru untuk memberikan pengalaman browsing yang lebih baik.</p>
                    <div class="info-date">
                        <i class="fas fa-calendar"></i>
                        <span>8 Januari 2024</span>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-folder-plus"></i>
                    </div>
                    <h3 class="info-title">Kategori Baru Ditambahkan</h3>
                    <p class="info-description">Kategori baru telah ditambahkan ke dalam sistem galeri untuk memudahkan pengorganisasian foto dan dokumentasi kegiatan sekolah.</p>
                    <div class="info-date">
                        <i class="fas fa-calendar"></i>
                        <span>5 Januari 2024</span>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="info-title">Workshop Guru dan Staf</h3>
                    <p class="info-description">Pelatihan peningkatan kompetensi guru dan staf dalam penggunaan teknologi digital untuk mendukung pembelajaran yang lebih efektif.</p>
                    <div class="info-date">
                        <i class="fas fa-calendar"></i>
                        <span>3 Januari 2024</span>
                    </div>
                </div>
                
            </div>

            <!-- Prestasi Sekolah (grid ringkas) -->
            <div class="awards-section">
                <div class="awards-header"><i class="fas fa-trophy"></i> Prestasi Sekolah</div>
                <div class="awards-grid">
                    <div class="award-card">
                        <div class="award-icon"><i class="fas fa-medal"></i></div>
                        <div>
                            <div class="award-title">Juara 1 Kompetisi Programming Nasional</div>
                            <div class="award-sub">2024 · Tim PPLG</div>
                        </div>
                    </div>
                    <div class="award-card">
                        <div class="award-icon"><i class="fas fa-medal"></i></div>
                        <div>
                            <div class="award-title">Juara 2 Lomba Jaringan Komputer</div>
                            <div class="award-sub">2024 · Tim TJKT</div>
                        </div>
                    </div>
                    <div class="award-card">
                        <div class="award-icon"><i class="fas fa-medal"></i></div>
                        <div>
                            <div class="award-title">Juara 3 Kompetisi Otomotif</div>
                            <div class="award-sub">2024 · Tim TO</div>
                        </div>
                    </div>
                    <div class="award-card">
                        <div class="award-icon"><i class="fas fa-medal"></i></div>
                        <div>
                            <div class="award-title">Juara 1 Lomba Pengelasan</div>
                            <div class="award-sub">2024 · Tim TPFL</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="achievement-section">
                <h2 class="achievement-title">Program Keahlian</h2>
                <div class="achievement-grid">
                    <div class="achievement-item">
                        <div class="achievement-icon"><i class="fas fa-code"></i></div>
                        <div class="achievement-text">PPLG<br><small>(Pengembangan Perangkat Lunak & Gim)</small></div>
                    </div>
                    <div class="achievement-item">
                        <div class="achievement-icon"><i class="fas fa-network-wired"></i></div>
                        <div class="achievement-text">TJKT<br><small>(Teknik Jaringan Komputer & Telekomunikasi)</small></div>
                    </div>
                    <div class="achievement-item">
                        <div class="achievement-icon"><i class="fas fa-industry"></i></div>
                        <div class="achievement-text">TPFL<br><small>(Teknik Pengelasan & Fabrikasi Logam)</small></div>
                    </div>
                    <div class="achievement-item">
                        <div class="achievement-icon"><i class="fas fa-car"></i></div>
                        <div class="achievement-text">TO<br><small>(Teknik Otomotif)</small></div>
                    </div>
                </div>
            </div>
            
            
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
